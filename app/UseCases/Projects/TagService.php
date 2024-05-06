<?php
namespace App\UseCases\Projects;

use App\Entity\Project\Project;
use App\Entity\Tag\Tag;
use Illuminate\Http\Request;

class TagService
{
    public function updateTags(Request $request, Project $project): void
    {
        $tags = json_decode($request['projectTags'], true) ?? [];
        $project->tags()->detach();
        $this->saveTags($tags, $project);
    }

    public function createTags(Request $request, Project $project) {
        $tags = json_decode($request['projectTags'], true) ?? [];
        $project->tags()->detach();
        $this->saveTags($tags, $project);
    }

    private function saveTags($tags, Project $project): void
    {
        foreach ($tags as $text) {
            $tag = Tag::query()->where('title', 'like', $text)->first();
                if (!$tag) {
                    $tag = Tag::create(['title' => $text]);
                }
            $project->addToTags($tag->id);
        }
    }
}
