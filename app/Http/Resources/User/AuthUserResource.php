<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => 'User',
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'avatarUrl' => $this->getAvatarUrl(),
            'filled' => $this->filled(),
            'superUser' => $this->isAdmin()
        ];
    }
}
