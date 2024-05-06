<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateProjectResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /** @var Project $this*/
        return [
            'id' => $this->id,
            'success' => true,
            'slug' => $this->slug,
            'favoritesCount' => $this?->favoritesCount($this?->id),
        ];
    }
}
