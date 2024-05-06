<?php

namespace App\Http\Resources\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateRequiredTeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->new?->id,
            'title' => $this->new?->title,
            'status' => $this->new?->status,
            'isHidden' => $this?->new?->pivot?->is_hidden === 0 ? false : true,
            'previous' => $this->old?->id,
        ];
    }
}
