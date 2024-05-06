<?php
namespace App\UseCases\Project;

use Carbon\Carbon;
use DomainException;
use App\Entity\User\User;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\User\Role\Role;
use App\Events\User\ApplyEvent;
use App\UseCases\Centrifugo\CentrifugoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriberService
{
    private $centrifuge;
    public function __construct(CentrifugoService $service)
    {
        $this->centrifuge = $service;
    }
    public function subscribers(Request $request): LengthAwarePaginator {
        $id = $request['id'] ?? "";
        return User::query()->whereHas('projectSubscribers', function (Builder $query) use ($id) {
            $query->where('project_id', $id);
            $query->whereNotNull('subscribed_at');
        })
            ->paginate();
    }

    // только не подтвержденные заявки
    public function applications(Request $request): LengthAwarePaginator {
        $project = findProjectWithSubscriber($request['id'] ?? "");
        return $project->projectSubscribers()
                ->wherePivot('project_id', 'like', $project->id)
                ->wherePivotNull('subscribed_at')
                ->paginate();
    }

    // кол-во заявок
    public function applicationCount(Request $request) {
        $project = findProjectWithSubscriber($request['id'] ?? "");
        return $project->projectSubscribers()
                ->wherePivot('project_id', 'like', $project->id)
                ->wherePivotNull('subscribed_at')
                ->count();
    }

    // подтвержденные заявки
    public function members(Request $request) {
        $id = $request['id'] ?? "";
        $project = findProjectWithSubscriber($id);

        $user = $project->projectSubscribers()
                ->wherePivot('project_id', 'like', $project->id)
                ->wherePivot('subscriber_id', 'like', $project?->user?->id)
                ->wherePivotNotNull('subscribed_at')
                ->paginate();
        $subscribers = $project->projectSubscribers()
                ->wherePivot('project_id', 'like', $project->id)
                ->wherePivot('subscriber_id', 'not like', $project?->user?->id)
                ->wherePivotNotNull('subscribed_at')
                ->orderByPivot('subscribed_at', 'desc')
                ->paginate();

        return $this->merge($user, $subscribers);
    }

    public function updateMember(Request $request) {
        $project = findAuthProject($request['id']);
        $subscriber = findActiveUser($request['subscriber']);
        $position = findRequirePositionWithOwnProject($request['position'], $project->id);
        $role = Role::query()->where('title', 'like', $request['role'])->first();
        if (!$role) {
            throw new DomainException(config('constants.something_went_wrong'));
        }
        $project->projectSubscribers()->updateExistingPivot($subscriber, [
            'role_id' => $role?->id,
            'subscribed_at' => Carbon::now(),
            'require_team_id' => $position->id
        ]);
        return $project
            ->projectSubscribers()
            ->wherePivot('subscriber_id', 'like', $subscriber?->id)
            ->first();;
    }

    public function subscribe(Request $request): void {
        $user = findAuthUser();
        $projectId = $request['id'];
        $position = $request['position'];
        $project = Project::query()
            ->where('id', 'like', $projectId)
            ->where('user_id', 'not like', $user->id)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->first();
        if (!$project) {
            throw new DomainException(config('constants.project_not_found'));
        }
        $tag = findRequirePositionWithOwnProject($position, $project->id);
        $project->addSubscriber($user->id, ['require_team_id' => $tag?->id]);

        event(new ApplyEvent($project, $user));
        $this->centrifuge->notifyApplyProject($project, $user);
    }

    public function subscribed(Request $request): User {
        $project = findAuthProject($request['id']);
        $subscriber = findActiveUser($request['subscriber']);
        $user = $project->projectSubscribers()->wherePivot('subscriber_id', 'like', $subscriber?->id)->first();

        if ($subscriber?->id !== $user?->id) {
            throw new DomainException(config('constants.something_went_wrong'));
        }
        if ($user?->pivot?->subscribed_at) {
            throw new DomainException(config('constants.application_already_accepted'));
        } else {
            $project->projectSubscribers()->updateExistingPivot($subscriber, [
                'subscribed_at' => Carbon::now(),
                'role_id' => findOrCreateRole(Role::PROJECT_GUEST)?->id,
            ]);
        }
        return $user;
    }

    // проекты на которые подписался пользователь
    public function getSignedProjects() {
        $role = Role::query()->where('title', 'like', Role::PROJECT_FOUNDER)->first();
        return Project::query()->whereHas('projectSubscribers', function (Builder $query) use($role) {
            $query->where('role_id', 'not like', $role?->id);
            $query->where('subscriber_id', authUser()->id);
            $query->whereNotNull('subscribed_at');
        })
            ->paginate(config('constants.projects_per_page'));
    }

    public function unsubscribed(Request $request): User {
        $project = findAuthProject($request['id']);
        $subscriber = findActiveUser($request['subscriber']);
        $user = $project->projectSubscribers()->wherePivot('subscriber_id', 'like', $subscriber?->id)->first();
        if ($subscriber?->id !== $user?->id) {
            throw new DomainException(config('constants.something_went_wrong'));
        }
        $project->removeSubscriber($subscriber->id);
        return $subscriber;
    }

    public function getRolesInTeam() {
        $roles = [];
        foreach (Role::rolesInTeamList() as $role) {
            $roles[] = $role['title'];
        }
        return Role::query()->whereIn('title', $roles)->get();
    }

    public function getSubscriber(Request $request): User {
        $id = $request['id'] ?? "";
        $project = $project = Project::query()
            ->where('id', 'like', $id)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->first();
        if (!$project) {
            throw new DomainException(config('constants.project_not_found'));
        }
        $user = $project?->projectSubscribers()
             ->wherePivot('project_id', 'like', $project?->id)
             ->wherePivot('subscriber_id', 'like', authUser()->id)
             ->wherePivotNotNull('subscribed_at')
            ->first();
        if (!$user) {
            throw new DomainException(config('constants.subscriber_not_found'));
        }
        return $user;
    }

    public function getPermissions(Request $request) {}

    private function merge(LengthAwarePaginator $collection1, LengthAwarePaginator $collection2)
    {
        $path = $collection2->path();
        $total = $collection2->total();
        $perPage = $collection2->perPage();
        $items = array_merge($collection1->items(), $collection2->items());

        $paginator = new LengthAwarePaginator($items, $total, $perPage, null,
            ['path' => $path]
        );
        return $paginator;
    }
}
