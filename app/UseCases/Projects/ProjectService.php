<?php
namespace App\UseCases\Projects;

use App\Entity\Project\Project;
use App\Entity\User\Role\Role;
use App\Entity\User\User;
use App\Events\Project\ProjectEvent;
use App\UseCases\Residence\ResidenceService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProjectService
{
    private $tagService;
    private $requireService;
    private $residenceService;


    public function __construct(
        TagService $tagService,
        RequireTeamTagsService $requireService,
        ResidenceService $residenceService,
        )
    {
        $this->tagService = $tagService;
        $this->requireService = $requireService;
        $this->residenceService = $residenceService;
    }

    // create project
    public function create(Request $request, string $status): Project
    {
        /** @var User $user */
        $user = authUser();
        return DB::transaction(function () use ($request, $user, $status) {
            /** @var Project $project */
            $project = Project::query()->make([
                'title' => $request['title'],
                'about' => $request['about'],
                'description' => $request['description'],
                'status' => $status,
                'slug' => $request['slug'],
            ]);
            $project->user()->associate($user);

            $project->saveOrFail();

            $_country = json_decode($request['country'], true);
            $_city = json_decode($request['city'], true);
            $this->residenceService->createOrUpdateProjectLocation($project,
                $_country['title'] ?? null, $request['region'], $request['city'],
                 $_country['id'] ?? null, $_city['regionId'] ?? null, $_city['id'] ?? null
            );

            $this->tagService->createTags($request, $project);
            $this->requireService->createTags($request, $project);

            $project->projectSubscribers()->attach($user, [
                'subscribed_at' => Carbon::now(),
                'role_id' => findOrCreateRole(Role::PROJECT_FOUNDER)->id,
            ]);

            //event(new ProjectEvent($project, $user, Project::STATUS_MODERATION));

            return $project;
        });
    }

    // edit project depend on project status
    public function edit(string $projectId, Request $request): Project
    {
        $project = findAuthProject($projectId);
        return DB::transaction(function () use ($request, $project) {
            switch ($project->status) {
                case Project::STATUS_DRAFT:
                case Project::STATUS_REJECTED:
                    break;
                case Project::STATUS_MODERATION:
                    $this->validate($request, $project);
                    event(new ProjectEvent($project, $project->user, Project::STATUS_MODERATION));
                    break;
                case Project::STATUS_ACTIVE:
                    $this->validate($request, $project);
                    $project->changeStatus(Project::STATUS_MODERATION);
                    event(new ProjectEvent($project, $project->user, Project::STATUS_MODERATION));
                    break;
            }
            if ($project?->status === Project::STATUS_DRAFT || $project?->status === Project::STATUS_REJECTED) {
                $project->update($request->only(['title', 'description', 'about']));
            } else if ($project?->slug === $request['slug']) {
                $project->update($request->only(['title', 'description', 'about']));
            } else {
                $project->update($request->only(['title', 'description', 'about', 'slug']));
            }

            $this->tagService->updateTags($request, $project);
            $this->requireService->updateTags($request, $project);

            $_country = json_decode($request['country'], true);
            $_city = json_decode($request['city'], true);
            $this->residenceService->createOrUpdateProjectLocation($project,
                $_country['title'] ?? null, $request['region'], $request['city'],
                 $_country['id'] ?? null, $_city['regionId'] ?? null, $_city['id'] ?? null
            );

            return $project;
        });
    }

    // remove project
    public function remove(string $id): Project
    {
        $project = findAuthProject($id);
        $project->update(['slug' => null]);
        $project->remove();
        event(new ProjectEvent($project, $project->user, Project::STATUS_DELETED));
        return $project;
    }

    // move project to draft
    public function onDraft(Request $request, string $projectId): Project
    {
        $project = findAuthProject($projectId);
        $this->updateProjectWithStatus($request, $project, Project::STATUS_DRAFT);
        return $project;
    }
    // move project to draft
    public function onDraftSimple(string $projectId): Project
    {
        $project = findAuthProject($projectId);
        $project->update([
            'status' => Project::STATUS_DRAFT,
            'slug' => generateDraftSlug()
        ]);
        // по умолчанию публикую все позиции
        $this->requireService->setVisibleToRequireTeams($project);
        event(new ProjectEvent($project, $project->user, Project::STATUS_DRAFT));
        return $project;
    }

    // move project to moderation
    public function onModeration(Request $request, string $projectId): Project
    {
        $project = $this->findProjectWithStatuses($projectId, Project::STATUS_DRAFT, Project::STATUS_REJECTED);
        return DB::transaction(function() use($project, $request) {
            $this->updateProjectWithStatus($request, $project, Project::STATUS_MODERATION);
            return $project;
        });
    }

    private function updateProjectWithStatus(Request $request, Project $project, string $status): void
    {
        if ($status === Project::STATUS_MODERATION) {
            $this->validate($request, $project);
        }
        $project->update([
            'title' => $request['title'],
            'about' => $request['about'],
            'description' => $request['description'],
            'status' => $status,
            'slug' => $request['slug'],
        ]);
        $this->tagService->updateTags($request, $project);
        $this->requireService->updateTags($request, $project);

        $_country = json_decode($request['country'], true);
        $_city = json_decode($request['city'], true);
        $this->residenceService->createOrUpdateProjectLocation($project,
            $_country['title'] ?? null, $request['region'], $request['city'],
            $_country['id'] ?? null, $_city['regionId'] ?? null, $_city['id'] ?? null
        );

        event(new ProjectEvent($project, $project->user, $status));
    }

    // get Active and Moderation projects
    public function getProjects()
    {
        return Project::query()
            ->where('status', 'like', Project::STATUS_ACTIVE)
            ->orWhere('status', 'like', Project::STATUS_MODERATION)
            ->orderByDesc('created_at')
            ->paginate(100);
    }

    // проекты авторизованного пользователя
    public function getProfileProjects(): LengthAwarePaginator
    {
        return authUser()
            ->projects()
            ->orderByDesc('created_at')
            ->paginate(config('constants.projects_per_page'));
    }

    // только активные проекты
    public function getAuthUserActiveProjects(): LengthAwarePaginator
    {
        return Project::query()
            ->where('user_id', authUser()->id)
            ->where('status', 'like', Project::STATUS_ACTIVE)
            ->orderByDesc('created_at')
            ->paginate(config('constants.projects_per_page'));
    }

    private function validate(Request $request, Project $project): void
    {
        $request->merge([
            'slug' => generateSlug($request['title'], 500)
        ]);
        if ($project?->slug === $request['slug']) {
            $request->validate([
                'about' => 'required|string',
                'description' => 'required|string|max:200',
            ]);
        } else {
            $request->validate([
                'about' => 'required|string',
                'description' => 'required|string|max:200',
                'slug' => ['string', 'unique:projects,slug', 'required'],
            ], ['slug.unique' => config('constants.project_title_exists')]);
        }
    }

    public function userDrafts(): LengthAwarePaginator
    {
        $user = authUser();
        $drafts = Project::query()
            ->where('user_id', $user->id)
            ->where('status', '=', Project::STATUS_REJECTED)
            ->orWhere(function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);
                $query->where('status', '=', Project::STATUS_DRAFT);
            })
            ->orderByDesc('created_at')
            ->paginate(config('constants.projects_per_page'));
        return $drafts;
    }

    // popular projects
    public function getProjectsSortedByCommentsCount(): LengthAwarePaginator
    {
        return Project::query()
            ->where('status', 'like', Project::STATUS_ACTIVE)
            ->orWhere('status', 'like', Project::STATUS_MODERATION)
            ->leftJoin('project_comments', 'projects.id', '=', 'project_comments.project_id')
            ->groupBy('projects.id')
            ->orderBy('comments_count', 'desc')
            ->selectRaw('projects.*, count(project_comments.id) as comments_count')
            ->paginate(config('constants.projects_per_page'));
    }

    // или проекты авторизованного пользователя
    // или любые проекты в статусе Active or Moderation
    public function findAnyProject(string $slug, ?User $auth): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()
                ->where('slug', $slug)
                ->where('user_id', $auth?->id)
                ->orWhere(function (Builder $query) use ($slug) {
                    $query->where('slug', $slug);
                    $query->where('status', Project::STATUS_ACTIVE);
                    $query->orWhere(function (Builder $query) use ($slug) {
                        $query->where('slug', $slug);
                        $query->where('status', Project::STATUS_MODERATION);
                    });
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }

    public function findProjectWithStatuses(string $id, string $status1, string $status2): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()
                ->where('id', $id)
                ->where('status', '=', $status1)
                ->orWhere(function (Builder $query) use ($id, $status2) {
                    $query->where('id', $id);
                    $query->where('status', '=', $status2);
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }

    // покинуть и проект и передать его подписчику c ролью (Project Admin)
    public function leaveProject(Request $request, string $projectId) {
        DB::transaction(function () use ($request, $projectId) {

            // получаю основателя и проверяю разрешение
            $project = findPivotProjectWithSubscriber($projectId);
            Gate::authorize('leave-project', $project->pivot?->role_id);
            // получаю подписчика
            $subscriber = findActiveUser($request['subscriber']);
            // вдруг id совпадают
            if ($subscriber?->id === authUser()->id) {
                throw new \DomainException(config('constants.something_went_wrong'));
            }

            // получаю проект подписчика
            $subsProject = $subscriber->projectSubscribers()
                ->wherePivot('project_id', 'like', $project->id)
                ->wherePivot('subscriber_id', 'like', $subscriber->id)
                ->wherePivotNotNull('subscribed_at')
                ->first();
            // если не совпадают/нет проекта у подписчика
            if ($project?->id !== $subsProject?->id) {
                throw new \DomainException(config('constants.something_went_wrong'));
            }
            // подписчик должен быть админом в проекте
            $admin = Role::query()->where('title', 'like', Role::PROJECT_ADMIN)->first();
            if ($admin?->id !== $subsProject?->pivot?->role_id) {
                throw new AuthorizationException();
            }

            $project->removeSubscriber(authUser()?->id);
            $project->projectSubscribers()->updateExistingPivot($subscriber, [
                'subscribed_at' => Carbon::now(),
                'role_id' => findOrCreateRole(Role::PROJECT_FOUNDER)?->id,
            ]);

            $project->user()->associate($subscriber);
            $project->save();
        });
    }

    public function getPopularProjects() {
        $projects = Project::query()
            ->where('status', 'like', 'Active')
            ->orWhere('status', 'like', 'Moderation')
            ->leftJoin('project_comments', 'projects.id', '=', 'project_comments.project_id')
            ->groupBy('projects.id')
            ->orderBy('comments_count', 'desc')
            ->selectRaw('projects.*, count(project_comments.id) as comments_count')
            ->paginate(10);
        return $projects;
    }

    public function getTitleOfProjects(User $author) {
        return $author->projects()
            ->whereIn('status', [Project::STATUS_ACTIVE, Project::STATUS_MODERATION])
            ->select(['id', 'title', 'slug'])
            ->get();
    }
}
