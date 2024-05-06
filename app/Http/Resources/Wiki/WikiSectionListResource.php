<?php

namespace App\Http\Resources\Wiki;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiSectionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'createdAt' => $this->created_at
        ];
    }
}
