<?php
namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectModalListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description
        ];
    }
}
