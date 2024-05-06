<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use App\Entity\Blog\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    private $blog;
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->blog?->id,
            'slug' => $this->blog?->slug,
            'title' => $this->blog?->title,
            'status' => $this->blog?->status,
            'createdAt' => $this->blog?->created_at,
            'description' => $this->blog?->description,
            'user' => [
                'id' => $this->blog?->user?->id,
                'firstname' => $this->blog?->user?->firstname,
                'lastname' => lastnameFormat($this->blog?->user?->lastname)
            ],
            'project' => [
                'id' => $this->blog?->project?->id,
                'slug' => $this->blog?->project?->slug,
                'title' => $this->blog?->project?->title,
                'author' => [
                    'id' => $this->blog?->project?->user?->id,
                ]
            ],
            'commentsCount' => $this->blog?->getCommentsCount(),
            'subject' => $this->blog?->subjects()->first()?->title,
            'favoritesCount' => $this->blog?->getFavoritesCount(),
            'hasInFavorites' => $this->blog->hasInFavorites($this->blog?->id)
        ];
    }
}
