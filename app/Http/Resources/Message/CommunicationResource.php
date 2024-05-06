<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\LinkCollectionResource;

class CommunicationResource extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return [
            'data' => CommunicationListResource::collection($this->collection),
            'links' => new LinkCollectionResource($this->linkCollection()),
            'meta' => [
                'total' => $this->total(),
            ],
        ];
    }
}
