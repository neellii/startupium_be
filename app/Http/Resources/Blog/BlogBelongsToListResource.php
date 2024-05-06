<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use App\Entity\User\User;
use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogBelongsToListResource extends JsonResource
{
    private $belongsTo;
    private $blogs;
    public function __construct(User | Project $belongsTo, LengthAwarePaginator $blogs)
    {
        $this->blogs = $blogs;
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
        $data['blogs']['data'] = BlogSimpleResource::collection($this->blogs);
        $data['blogs']['links']['first'] = $this->blogs->toArray()['links'][1]['url'];
        $data['blogs']['links']['next'] = $this->blogs->toArray()['links'][count($this->blogs->toArray()['links']) - 1]['url'];
        $data['blogs']['links']['last'] = $this->blogs->toArray()['links'][count($this->blogs->toArray()['links']) - 2]['url'];
        $data['blogs']['links']['prev'] = $this->blogs->toArray()['links'][0]['url'];
        $data['blogs']['meta']['current_page'] = $this->blogs->toArray()['current_page'];
        $data['blogs']['meta']['from'] = $this->blogs->toArray()['from'];
        $data['blogs']['meta']['last_page'] = $this->blogs->toArray()['last_page'];
        $data['blogs']['meta']['links'] = $this->blogs->toArray()['links'];
        $data['blogs']['meta']['path'] = $this->blogs->toArray()['path'];
        $data['blogs']['meta']['per_page'] = $this->blogs->toArray()['per_page'];
        $data['blogs']['meta']['to'] = $this->blogs->toArray()['to'];
        $data['blogs']['meta']['total'] = $this->blogs->toArray()['total'];
        return $data;
    }
}
