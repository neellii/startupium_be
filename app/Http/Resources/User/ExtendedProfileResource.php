<?php

namespace App\Http\Resources\User;

use App\Entity\User\Quality\Quality;
use App\Entity\User\Skill\Skill;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Residence\CityResource;
use App\Http\Resources\Residence\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtendedProfileResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'bio' => $this->bio,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'avatarUrl' => $this->getAvatarUrl(),
            'city' => new CityResource($this->city),
            'country' => new CountryResource($this->city?->country),
            'socials' => $this->socials,
            'desiredPosition' => $this->desired_position,
            'skills' =>  Skill::query()->whereHas('skills', function (Builder $query) {
                $query->where('user_id', $this->id);
            })->get(),
            'qualities' => Quality::query()->whereHas('qualities', function (Builder $query) {
                $query->where('user_id', $this->id);
            })->get(),
            'careers' => $this->carrers,
            'rolesInProject' => $this->rolesInProject()->first()
        ];
    }
}
