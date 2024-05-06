<?php

namespace App\Http\Resources\Message;

use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageAuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            'id' => $this?->id,
            'firstname' => $this?->firstname,
            'lastname' => lastnameFormat($this?->lastname),
            'createdAt' => $this?->created_at,
            'avatarUrl' => $this?->getAvatarUrl(),
        ];
    }
}
