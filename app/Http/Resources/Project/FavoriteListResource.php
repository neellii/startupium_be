<?php
namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'project_id' => $this->id
        ];
    }
}
