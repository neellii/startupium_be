<?php

namespace App\Http\Resources\Wiki;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiSectionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'parent_id' => $this->parent_id,
            'createdAt' => $this->created_at
        ];
    }
}
