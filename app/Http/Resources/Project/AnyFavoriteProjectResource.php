<?php
namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class AnyFavoriteProjectResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'type' => 'like',
            'id' => $this->id,
            'title' => $this->title
        ];
    }
}
