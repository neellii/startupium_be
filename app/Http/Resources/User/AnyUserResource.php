<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use App\Http\Resources\Residence\CityResource;
use App\Http\Resources\Residence\CountryResource;
use App\Http\Resources\Residence\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnyUserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'bio' => $this->bio ?? "",
            'createdAt' => $this->created_at,
            'avatarUrl' => $this->getAvatarUrl(),
            'isOnline' => $this->isOnline(),
            'lastOnlineAt' => $this->last_online_at,
            'desiredPosition' => $this->desired_position ?? "",
            'city' => new CityResource($this->city()->first()),
            'region' => new RegionResource($this->region()->first()),
            'country' => new CountryResource($this->country()->first()),
            'socials' => $this->socials,
            'careers' => $this->carrers,
            'rolesInProject' => $this->getRollesInProject(),
            'skills' =>  $this->getSkills(),
            'qualities' => $this->getQualities(),
            'filled' => $this->filled()
        ];
    }
}
