<?php
namespace App\Http\Resources\Login;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginSuccessResource extends JsonResource
{
    private $token;
    private $user;
    private $refreshToken;

    public function __construct($resource, $token, $refreshToken)
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->user = $resource;
        $this->refreshToken = $refreshToken;
    }

    public function toArray($request)
    {
        return [
            'success' => true,
            'token' => $this->token['token'] ?? "",
            'expiresAt' => $this->token['expiresAt'] ?? "",
            'tokenType' => 'Bearer',
            'user' => [
                'id' => $this->user->id,
                'role' => $this->user->role,
                'email' => $this->user->email,
                'firstname' => $this->user->firstname,
                'lastname' => lastnameFormat($this->user->lastname),
                'avatarUrl' => $this->user->getAvatarUrl(),
                'filled' => boolval($this->user->firstname) && boolval($this->user->desired_position)
            ],
            'refreshToken' => $this->refreshToken
        ];
    }
}
