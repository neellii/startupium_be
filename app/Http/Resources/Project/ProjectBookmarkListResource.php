<?php
namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBookmarkListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'id' => $this->id
        ];
    }
}
