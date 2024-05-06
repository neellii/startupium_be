<?php
namespace App\Http\Resources\Register;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterSuccessResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'success' => true,
            'email' => $this->email,
            'emailVerified' => $this->hasVerifiedEmail(),
            'message' => config('constants.account_successfully_created')
        ];
    }
}
