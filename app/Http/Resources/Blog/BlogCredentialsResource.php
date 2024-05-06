<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use App\Entity\Blog\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCredentialsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Blog $this */
        $subject = $this?->subjects()->first();
        $project = $this->project()->select(['id', 'title', 'slug'])->first();
        $user = $this->user()->select(['id', 'firstname', 'lastname'])->first();
        return [
            'title' => $this?->title,
            'description' => $this?->description,
            'subject' => [
                'id' => $subject?->id,
                'title' => $subject?->title,
            ],
            'author' => [
                'id' => $project?->id ?? $user?->id,
                'slug' => $project?->slug ?? "",
                'title' => $project->title ?? trim($user?->firstname . ' ' . lastnameFormat($user?->lastname))
            ]
        ];;
    }
}
