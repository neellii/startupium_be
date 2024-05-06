<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateUserDataResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'bio' => $this->bio,
            'email' => $this->email,
            'passwordChangedAt' => $this->password_changed_at,
            'protected' => $this->password ? true : false,
            'isEmailChecked' => false,
        ];
    }
}
