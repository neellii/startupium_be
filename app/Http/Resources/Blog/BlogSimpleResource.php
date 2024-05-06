<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use App\Entity\Blog\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogSimpleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Blog $this */
        return [
            'id' => $this?->id,
            'slug' => $this?->slug,
            'title' => $this?->title,
            'status' => $this?->status,
            'createdAt' => $this?->created_at,
            'description' => $this?->description,
            'user' => [
                'id' => $this?->user?->id,
            ],
            'project' => [
                'id' => $this?->project?->id,
                'author' => [
                    'id' => $this?->project?->user?->id,
                ]
            ],
            'commentsCount' => $this?->getCommentsCount(),
            'favoritesCount' => $this?->getFavoritesCount(),
            'subject' => $this?->subjects()->first()?->title,
        ];;
    }
}
