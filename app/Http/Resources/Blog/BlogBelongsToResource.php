<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use App\Entity\Blog\Blog;
use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogBelongsToResource extends JsonResource
{
    private $belongsTo;
    private $blog;
    public function __construct(User | Project $belongsTo, ?Blog $blog)
    {
        $this->blog = $blog;
        $this->belongsTo = $belongsTo;
    }

    public function toArray(Request $request): array
    {
        $data = [];
        if ($this->belongsTo?->firstname) {
            $data['belongsTo']['id'] = $this->belongsTo?->id;
            $data['belongsTo']['authorId'] = $this->belongsTo?->id;
            $data['belongsTo']['avatarUrl'] = $this->belongsTo?->getAvatarUrl();
            $data['belongsTo']['publicationCount'] = $this->belongsTo->blogsCount();
            $data['belongsTo']['title'] = $this->belongsTo?->firstname . ' ' . $this->belongsTo?->lastname ?? "";
        }
        if ($this->belongsTo?->title) {
            $data['belongsTo']['id'] = $this->belongsTo?->id;
            $data['belongsTo']['slug'] = $this->belongsTo?->slug;
            $data['belongsTo']['title'] = $this->belongsTo?->title;
            $data['belongsTo']['authorId'] = $this->belongsTo?->user?->id;
            $data['belongsTo']['publicationCount'] = $this->belongsTo->blogsCount();
        }
        if ($this->blog?->id) {
            $data['id'] = $this->blog?->id;
            $data['slug'] = $this->blog?->slug;
            $data['title'] = $this->blog?->title;
            $data['status'] = $this->blog?->status;
            $data['createdAt'] = $this->blog?->created_at;
            $data['description'] = $this->blog?->description;
            if ($this->belongsTo?->firstname) {
                $data['user']['id'] = $this->belongsTo?->id;
                $data['user']['firstname'] = $this->belongsTo?->firstname;
                $data['user']['lastname'] = lastnameFormat($this->belongsTo?->lastname);
                $data['user']['avatarUrl'] = $this->belongsTo?->getAvatarUrl();
            }
            if ($this->belongsTo?->title) {
                $data['project']['id'] = $this->belongsTo?->id;
                $data['project']['slug'] = $this->belongsTo?->slug;
                $data['project']['title'] = $this->belongsTo?->title;
                $data['project']['author']['id'] = $this->belongsTo?->user?->id;
            }
            $data['commentsCount'] = $this->blog?->getCommentsCount();
            $data['favoritesCount'] = $this->blog?->getFavoritesCount();
            $data['subject'] = $this->blog?->subjects()->first()?->title;
            $data['hasInFavorites'] = $this->blog?->hasInFavorites($this->blog?->id);
        }
        return $data;
    }
}
