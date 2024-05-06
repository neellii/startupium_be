<?php

namespace App\Http\Resources\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequireForTeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id,
            'title' => $this?->title,
            'status' => $this?->status,
            'isHidden' => $this?->pivot?->is_hidden === 0 ? false : true
        ];
    }
}
