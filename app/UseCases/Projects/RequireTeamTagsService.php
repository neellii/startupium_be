<?php
namespace App\UseCases\Projects;

use DomainException;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\RequireTeam\RequireTeam;
use Illuminate\Support\Facades\DB;

class RequireTeamTagsService {

    public function getTags(string $projectId) {
        $project = findProjectWithSubscriber($projectId);
        return $project->requireTeams()->get();
    }

    public function createPosition(Request $request, string $projectId): RequireTeam {
        $title = $request['title'];
        $project = findAuthProject($projectId);
        $tag = RequireTeam::query()->where('title', 'like', $title)->first();
        if (!$tag) {
            $tag = RequireTeam::create(['title' => $title]);
        }
        $project->addToRequireTeamTags($tag->id);
        return $project->requireTeams()->where('id', 'like', $tag->id)->first();
    }

    public function updatePosition(Request $request, string $projectId) {
        return DB::transaction(function () use ($request, $projectId) {
            $title = $request['title'];
            $tagId = $request['tagId'] ?? "";
            $project = findAuthProject($projectId);

            $tag = $project->requireTeams()->where('id', 'like', $tagId)->first();
            if (!$tag) {
                throw new DomainException('Такая специальность отсутсвует.');
            }
            if ($tag?->title !== $title) {
                $new = RequireTeam::query()->where('title', 'like', $title)->first();
                if (!$new) {
                    $new = RequireTeam::create(['title' => $title]);
                }
                $project->requireTeams()->detach($tag);
                $project->requireTeams()->attach($new, ['is_hidden' => $tag->pivot?->is_hidden]);
                $new = $project->requireTeams()->where('id', 'like', $new->id)->first();
            } else {
                $new = $tag;
            }
        return (object) ['old' => $tag, 'new' => $new];
        });
    }

    public function deletePosition(Request $request, string $projectId) {
        $id = $request['tagId'] ?? "";
        $project = findAuthProject($projectId);
        $tag = RequireTeam::query()->where('id', 'like', $id)->first();
        $project->removeFromRequireTeamTags($tag?->id);
        return $tag;
    }

    public function switchVisibleUnVisibleTag(Request $request, string $projectId) {
        $id = $request['tagId'] ?? "";
        $project = findProjectWithSubscriber($projectId);
        $tag = $project->requireTeams()->where('id', 'like', $id)->first();
        if (!$tag) {
            throw new DomainException("Ничего не найдено.");
        }
        if ($tag?->pivot?->is_hidden === 0) {
            $project->requireTeams()->updateExistingPivot($tag, [
                'is_hidden' => true
            ]);
        } else {
            $project->requireTeams()->updateExistingPivot($tag, [
                'is_hidden' => false
            ]);
        }
        return $project->requireTeams()->where('id', 'like', $id)->first();
    }

    public function createTags(Request $request, Project $project): void {
        // массив названий тегов
        $tags = json_decode($request['requireForTeamTags'], true) ?? [];
        //$this->validate($tags);
        $project->requireTeams()->detach();
        $this->saveTags($tags, $project);
    }

    public function updateTags(Request $request, Project $project): void
    {
        // массив названий тегов
        $tags = json_decode($request['requireForTeamTags'], true);
        $this->saveTags($tags, $project);
    }

    public function setVisibleToRequireTeams(Project $project): void {
        $requires = $project->requireTeams()->get();
        foreach($requires as $tag) {
            $project->requireTeams()->updateExistingPivot($tag, ['is_hidden' => false]);
        }
    }

    private function saveTags($tags, Project $project): void
    {
        if ($project->status !== Project::STATUS_DRAFT) {
            // удаляю опубликованные позиции
            $project->requireTeams()->wherePivot('is_hidden', 0)->detach();
        } else {
            // или все если проект черновик
            $project->requireTeams()->detach();
        }
        // обхожу массив тегов от фронта
        foreach ($tags as $text) {
            // ищу или создаю позицию
            $tag = RequireTeam::query()->where("title", 'like', $text ?? "")->first();
            if (!$tag) {
                $tag = RequireTeam::create(['title' => $text]);
            }
            // сохраняю
            if (!$project->hasInRequire($tag->id)) {
                $project->requireTeams()->attach($tag);
            }
        }
    }
}
