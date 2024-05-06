<?php
namespace App\UseCases\Projects;

use App\Entity\Project\Project;
use App\Entity\User\User;
use App\Events\Project\ProjectEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookmarkService
{
    // add to bookmarks
    public function add(User $user, string $projectId): Project
    {
        $project = saveData($user, $projectId, 'addToBookmarks');

        /** @var Project $project */
        event(new ProjectEvent($project, $user, Project::PROJECT_TO_BOOKMARKS));
        return $project;
    }

    // delete from bookmarks
    public function remove(User $user, string $projectId): Project
    {
        $project = saveData($user, $projectId, 'removeFromBookmarks');

        /** @var Project $project */
        //event(new ProjectEvent($project, $user, Project::PROJECT_FROM_BOOKMARKS));
        return $project;
    }

    public function bookmarks(string $projectId): Collection
    {
        $user = authUser();
        return Project::query()->whereHas('bookmarks', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('id', $projectId)
            ->get();
    }

    public function allBookmarks(): LengthAwarePaginator
    {
        $user = authUser();
        return Project::query()->whereHas('bookmarks', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id)->orderByDesc('created_at');
        })->paginate(config('constants.projects_per_page'));
    }
}
