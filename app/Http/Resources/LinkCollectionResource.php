<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'first' => $this[1]['url'],
            'next' => $this[$this->count() - 1]['url'],
            'last' => $this[$this->count() - 2]['url'],
            'prev' => $this[0]['url']
        ];
    }
}
