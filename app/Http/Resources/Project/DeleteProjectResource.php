<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class DeleteProjectResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /** @var Project $this*/
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'success' => true,
            'favoritesCount' => 0,
        ];
    }
}
