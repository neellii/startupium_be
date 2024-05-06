<?php

namespace App\Http\Resources\Wiki;

use Illuminate\Http\Request;
use App\Entity\Wiki\WikiArticle;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiArticleDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var WikiArticle $this  */
        return [
            'id' => $this?->id,
            'title' => $this?->title,
            'text' => $this?->text,
            'section_id' => $this?->section_id,
            'createdAt' => $this?->created_at,
            'hasDefault' => $this->hasDefaultArticle()
        ];
    }
}
