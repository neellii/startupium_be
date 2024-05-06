<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'createdAt' => $this->created_at,
            'avatarUrl' => $this->getAvatarUrl(),
        ];
    }
}
