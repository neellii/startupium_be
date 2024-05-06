<?php
namespace App\Http\Resources\Login;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginErrorResource extends JsonResource
{
    private $message;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->message = $resource;
    }

    public function toArray($request)
    {
        return [
            'success' => false,
            'error' => $this->message
        ];
    }
}
