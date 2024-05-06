<?php
namespace App\Http\Resources\Projects;

use App\Entity\Project\Project;
use App\Http\Resources\Residence\CityResource;
use App\Http\Resources\Residence\CountryResource;
use App\Http\Resources\Residence\RegionResource;
use App\Http\Resources\User\SubscriberListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'about' => $this->about,
            'projectTags' => $this->getTags(),
            'requireForTeamTags' => $this->getRequireForTeamTags(),
            'description' => $this->description,
            'status' => $this->status,
            'reasonOfReject' => $this->reason,
            'createdAt' => $this->created_at,
            'author' => [
                'id' => $this->user->id,
                'firstname' => $this->user->firstname,
                'lastname' => lastnameFormat($this->user->lastname),
                'avatarUrl' => $this->user->getAvatarUrl(),
                'isOnline' => $this->user->isOnline(),
                'lastOnlineAt' => $this->user->last_online_at,
            ],
            'location' => [
                'city' => new CityResource($this->city()->first()),
                'region' => new RegionResource($this->region()->first()),
                'country' => new CountryResource($this->country()?->first())
            ],
            'hasInFavorites' => $this->hasInFavorites($this->id),
            'favoritesCount' => $this->favoritesCount($this->id),
            'team' => SubscriberListResource::collection(getSubscribersWithoutAuthor($this)),
        ];
    }
}
