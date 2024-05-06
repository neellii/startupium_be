<?php

namespace App\Http\Resources\Combine;

use App\Http\Resources\Projects\ActiveProjectListResource;
use App\Http\Resources\User\UserListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CombineResource extends JsonResource
{
    public function toArray($request)
    {
        $email = $this->email;
        if ($email) {
            return new UserListResource($this);
        }
        return new ActiveProjectListResource($this);
    }
}
