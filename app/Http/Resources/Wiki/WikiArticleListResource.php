<?php

namespace App\Http\Resources\Wiki;

use Illuminate\Http\Request;
use App\Entity\Wiki\WikiArticle;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiArticleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var WikiArticle $this  */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'section' => $this->wikiSection?->title,
            'createdAt' => $this->created_at
        ];
    }
}
