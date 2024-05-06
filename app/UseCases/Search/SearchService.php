<?php
namespace App\UseCases\Search;

use App\Entity\User\User;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\Tag\Tag;
use App\Entity\User\Skill\Skill;
use App\Entity\User\Status\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    // поиск проектов по названию
    // - поиск проектов у авторизованных пользователей осуществляется в зависимости от типа пректа
    // - поиск проектов у не авторизованных пользователей осуществляется только в статусе активный проект
    // или на модерации
    public function search(Request $request, ?User $user): LengthAwarePaginator
    {
        $type = $request['type'];
        $value = $request['value'];

        $buider = Project::query()->where('title', 'like', '%' . $value . '%');
        // для не авторизованного пользователя
        if (!$type || !$user) {
            $buider
                ->where('status', Project::STATUS_MODERATION)
                ->orWhere(function (Builder $query) use ($value) {
                    $query->where('title', 'like', '%' . $value . '%');
                    $query->where('status', Project::STATUS_ACTIVE);
                });
        } else {
            // для авторизованного пользователя
            if ($type && $user) {
                switch ($type) {
                    case 'projects':
                        $buider->where('user_id', $user->id);
                        break;
                    case 'drafts':
                        $buider->where('user_id', $user->id);
                        $buider->where('status', Project::STATUS_DRAFT);
                        break;
                    case 'bookmarks':
                        $buider->whereHas('bookmarks', function (Builder $query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                        break;
                }
            }
        }
        return $buider
            ->orderByDesc('created_at')
            ->paginate(config('constants.projects_per_page'));
    }

    // поиск проектов
    public function projectsResults(Request $request): LengthAwarePaginator {

        $sortBy = $request['projectsSortBy'];
        $searchBy = $request['projectsSearchBy'] ?? [];
        $position = $request['position'];
        $list = $request['values'] ?? "";
        $value = $request['value'] ?? "";

        $city = $request['city'] ?? "";
        $cityId = $request['cityId'] ?? "";
        $regionId = $request['regionId'] ?? "";
        $country = $request['country'] ?? "";
        $countryId = $request['countryId'] ?? "";

        $query = $this->projectsQuery();
        // поиск по тегам, при наличии массива тегов
        if ($this->checkSearchBy($searchBy, 'по тегам') && ($value || $list)) {
            $values = is_array($list) ? array_filter(array_unique([...$list, $value])) : [$value];
                $tags = Tag::query()
                ->whereIn('title', [...$values])
                ->join('tags_ref', 'tags_ref.tag_id', '=', 'tags.id')
                ->groupBy('tags_ref.project_id')
                ->selectRaw('count(project_id) as total, project_id');
            $query->joinSub($tags, 'tags', function (JoinClause $join) use ($values) {
                $join->on('projects.id', '=', 'tags.project_id');
                $join->where('tags.total', '=', count($values));
            });
        // иначе поиск по тексту и описанию проекта
        } else if (is_array($searchBy) && in_array("по названию", $searchBy) && in_array("по описанию", $searchBy)) {
            $query->where(function (Builder $buider) use ($value) {
                $buider->where('projects.title', 'like', '%' . $value . '%');
                $buider->orWhere('description', 'like', '%' . $value . '%');
            });
        } else if (is_array($searchBy) && in_array("по названию", $searchBy)) {
                $query->where('projects.title', 'like', '%' . $value . '%');
        } else if (is_array($searchBy) && in_array("по описанию", $searchBy)) {
            $query->where('description', 'like', '%' . $value . '%');
    }

        if ($this->checkSortBy($sortBy, 'по популярности')) {
            $query->leftJoin('project_comments', 'projects.id', '=', 'project_comments.project_id');
            $query->groupBy('projects.id');
            $query->orderBy('comments_count', 'desc');
            $query->selectRaw('projects.*, count(project_comments.id) as comments_count');
        } else {
            $query->orderByDesc('created_at');
            $query->selectRaw('projects.*');
        }

        if ($country) {
            $query->join('project_residence_ref', 'project_residence_ref.project_id', 'like', 'projects.id');

            $query->where('project_residence_ref.country_id', 'like', $countryId);
            if ($regionId) {
                $query->where('project_residence_ref.region_id', 'like', $regionId);
            }
            if ($city && $cityId) {
                $query->where('project_residence_ref.city_id', 'like', $cityId);
            }
        }

        if ($position) {
            $users = $this->activeUsersQuery()
                ->where('desired_position', $position)
                ->select('users.desired_position as position', 'users.id as user_id');
            $query->joinSub($users, 'users', function (JoinClause $join) {
                $join->on('projects.user_id', '=', 'users.user_id');
            });
        }

        return $query->paginate(config("constants.projects_per_page"));
    }

    // поиск пользователей
    public function usersResults(Request $request) {

        $list = $request['values'] ?? "";
        $value = $request['value'] ?? "";
        $sortBy = $request['usersSortBy'] ?? "";
        $usersSearchBy = $request['usersSearchBy'] ?? "";
        $role = $request['role'];
        $position = $request['position'];

        $city = $request['city'] ?? "";
        $cityId = $request['cityId'] ?? "";
        $regionId = $request['regionId'] ?? "";
        $country = $request['country'] ?? "";
        $countryId = $request['countryId'] ?? "";

        $query = $this->activeUsersQuery();
        // поиск по скиллам пользователей
        if ($this->checkSearchBy($usersSearchBy, 'по тегам') && ($value || $list)) {
            $values = is_array($list) ? array_filter(array_unique([...$list, $value])) : [$value];
            $skills = Skill::query()
                ->whereIn('title', [...$values])
                ->join('user_skills_ref', 'user_skills_ref.skill_id', '=', 'skills.id')
                ->groupBy('user_skills_ref.user_id')
                ->selectRaw('count(user_id) as total, user_id');
            $query->joinSub($skills, 'skills', function (JoinClause $join) use ($values) {
                $join->on('users.id', '=', 'skills.user_id');
                $join->where('skills.total', '=', count($values));
            });
        }
        // по имени или фамилии
        else if ($this->checkSearchBy($usersSearchBy, 'по имени')) {
            $query->where(function (Builder $buider) use ($value) {
                $buider->where('firstname', 'like', '%' . $value . '%');
                $buider->orWhere('lastname', 'like', '%' . $value . '%');
            });
        }

        if ($this->checkSortBy($sortBy, 'по последнему посещению')) {
            $query->orderByDesc('users.last_online_at');
        } else {
            $query->orderByDesc('users.created_at');
        }

        if ($country) {
            $query->join('user_residence_ref', 'user_residence_ref.user_id', 'like', 'users.id');

            $query->where('user_residence_ref.country_id', 'like', $countryId);
            if ($regionId) {
                $query->where('user_residence_ref.region_id', 'like', $regionId);
            }
            if ($city && $cityId) {
                $query->where('user_residence_ref.city_id', 'like', $cityId);
            }
        }

        if ($position) {
            $query->where('desired_position', $position);
        }

        if ($role) {
            if ($this->validateRole($role)) {
                $query->join('user_role_in_project', 'user_role_in_project.user_id', '=', 'users.id');
                $query->where(function (Builder $buider) use ($role) {
                    if ($role == 'investor') {
                        $buider->where('user_role_in_project.investor', $role == 'investor');
                        return;
                    }
                    if ($role == 'trainee') {
                        $buider->where('user_role_in_project.trainee', $role == 'trainee');
                        return;
                    }
                    if ($role == 'founder') {
                        $buider->where('user_role_in_project.founder', $role == 'founder');
                        return;
                    }
                    if ($role == 'seeker') {
                        $buider->where('user_role_in_project.seeker', $role == 'seeker');
                        return;
                    }
                    if ($role == 'mentor') {
                        $buider->where('user_role_in_project.mentor', $role == 'mentor');
                        return;
                    }
            });
            } else {
                return [];
            }
        }
        $query->select('users.*');
        return $query->paginate(config('constants.users_per_page'));
    }

    private function activeUsersQuery () {
        return User::query()->join('user_status', function ($join) {
            $join->on('users.id', '=', 'user_status.user_id')
                ->where('user_status.status', '=', Status::STATUS_ACTIVE);
        });
    }

    private function projectsQuery() {
        return Project::query()
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE]);
    }

    private function validateRole($role): bool {
      $value =
        $role == 'mentor' ||
        $role == 'trainee' ||
        $role == 'founder' ||
        $role == 'seeker' ||
        $role == 'investor';
      return $value;
    }

    private function checkSortBy($sortBy, $word): bool {
      if (is_array($sortBy)) {
        return $sortBy[0] === $word;
      } else if (is_string($sortBy)) {
        return $sortBy === $word;
      }
      return false;
    }

    private function checkSearchBy($searchBy, $word): bool {
        if (is_array($searchBy)) {
          return $searchBy[0] === $word;
        } else if (is_string($searchBy)) {
          return $searchBy === $word;
        }
        return false;
    }
}
