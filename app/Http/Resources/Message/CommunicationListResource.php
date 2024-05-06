<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use App\Entity\Chat\Communication;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Communication $this */
        return [
            'id' => $this->id,
            'author' => new MessageAuthorResource($this->getAuthor($this->user_id)),
            'projectId' => $this->project_id,
            'text' => $this->text,
            'read' => $this->read,
            'createdAt' => $this->created_at
        ];
    }
}
