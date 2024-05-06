<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailVerificationResource extends JsonResource
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function toArray($request)
    {
        return [
            'message' => config('constants.email_successfully_verified'),
            'verified' => true,
            //'token' => $this->token,
        ];
    }
}
