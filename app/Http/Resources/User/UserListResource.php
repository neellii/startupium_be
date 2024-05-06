<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'type' => 'user',
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'avatarUrl' => $this->getAvatarUrl(),
            'isOnline' => $this->isOnline(),
            'desiredPosition' => $this->desired_position,
            'lastOnlineAt' => $this->last_online_at,
            'createdAt' => $this->created_at,
            'skills' => SkillListResource::collection($this->skills()->orderByDesc('id')->get())
        ];
    }
}
