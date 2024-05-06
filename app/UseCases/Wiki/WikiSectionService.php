<?php
namespace App\UseCases\Wiki;

use App\Entity\Project\Project;
use App\Entity\Wiki\WikiSection;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WikiSectionService
{
    public function getSections(string $project_id) {
        $project = findProjectWithSubscriber($project_id);
        return $project->wikiSections()
            ->where('project_id', $project->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getGroupedSections(Project $project) {
        return $project->wikiSections()
            ->where('project_id', $project?->id)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('parent_id');

    }

    public function createSection(Request $request, string $project_id): WikiSection {

        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('create-section', $project?->pivot?->role_id);

        $title = $request['title'];
        $sectionId = $request['sectionId'];

        $this->isSectionAlreadyExists($project, $title);

        if ($sectionId) {
            // что есть родитель
            $c_s = $project->wikiSections()->where('id', 'like', $sectionId)->first();
            if (!$c_s) {
                throw new DomainException(config('constants.wiki_section_not_found'));
            }
            $nesting = $c_s->nesting;
            if ($nesting >= 3) {
                return $this->newSection($project, $title, 3, $c_s->parent_id);
            }
            else {
                return $this->newSection($project, $title, ($nesting + 1), $c_s->id);
            }
        } else {
            return $this->newSection($project, $title, 0);
        }
    }

    public function deleteSection(Request $request, string $project_id): WikiSection {
        $id = $request['sectionId'];
        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('delete-section', $project?->pivot?->role_id);
        $section = $this->findSectionById($id, $project);
        $section->delete();
        return $section;
    }

    public function updateSection(Request $request, string $project_id): WikiSection {
        $title = $request['title'] ?? "";
        $sectionId = $request['sectionId'] ?? "";
        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('edit-section', $project?->pivot?->role_id);
        $section = $this->findSectionById($sectionId, $project);
        if ($section?->title === $title) {
            throw new DomainException(config('constants.wiki_section_already_exists'));
        }
        $section->update([
            'title' => $title
        ]);
        return $section;
    }

    private function findSectionByTitle(string $section, $project) {
        $section = $project->wikiSections()
            ->where('title', $section)
            ->where('project_id', $project->id)
            ->first();
        if (!$section) {
            throw new DomainException(config('constants.wiki_section_not_found'));
        }
        return $section;
    }

    private function findSectionById(string $id, $project) {
        $section = $project->wikiSections()
            ->where('id', 'like', $id)
            ->where('project_id', $project->id)
            ->first();
        if (!$section) {
            throw new DomainException(config('constants.wiki_section_not_found'));
        }
        return $section;
    }

    private function newSection(Project $project, string $title, $nesting, $parent_id = null): WikiSection {
        return DB::transaction(function () use ($project, $title, $parent_id, $nesting) {
            $section = WikiSection::query()->make([
                'title' => $title,
                'type' => "Section",
                'nesting' => $nesting,
                'parent_id' => $parent_id
            ]);
            $section->project()->associate($project);
            $section->saveOrFail();
            return $section;
        });
    }

    private function isSectionAlreadyExists(Project $project, string $title) {
        $s = $project->wikiSections()->where('title', $title)->where('project_id', $project->id)->first();
        if ($s) {
            throw new DomainException(config('constants.wiki_section_already_exists'));
        }
    }
}
