<?php
namespace App\UseCases\Projects;

use App\Entity\Project\Project;
use App\Entity\User\User;
use App\Events\Project\ProjectEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FavoriteService
{
    // add to favorites
    public function add(User $user, string $projectId): Project
    {
        $project = saveData($user, $projectId, 'addToFavorites');

        /** @var Project $project */
        event(new ProjectEvent($project, $user, Project::PROJECT_TO_FAVORITES));
        return $project;
    }

    // remove from favorites
    public function remove(User $user, string $projectId): Project
    {
        $project = saveData($user, $projectId, 'removeFromFavorites');

        /** @var Project $project */
       //event(new ProjectEvent($project, $user, Project::PROJECT_FROM_FAVORITES));
        return $project;
    }

    // get favorites
    public function favorites(string $projectId): Collection
    {
        $user = authUser();
        return Project::query()->whereHas('favorites', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('id', $projectId)
            ->get();
    }

    // кол-во понравившихся
    public function projectFavoritesCount(string $projectId): int
    {
        $project = findProject($projectId);
        return $project->favorites()->get()->count();
    }
}
