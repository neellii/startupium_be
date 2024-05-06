<?php
namespace App\Http\Resources\Projects;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusProjectListResources extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'commentsCount' => $this->getCommentsCount(),
            'favoritesCount' => $this->favoritesCount($this->id),
            'status' => $this->status,
            'type' => 'project',
        ];
    }
}
