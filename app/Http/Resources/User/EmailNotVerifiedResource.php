<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailNotVerifiedResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'emailVerified' => $this->hasVerifiedEmail(),
            'email' => $this->email
        ];
    }
}
