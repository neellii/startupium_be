<?php
namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use App\Http\Resources\Residence\CityResource;
use App\Http\Resources\Residence\CountryResource;
use App\Http\Resources\Residence\RegionResource;
use App\Http\Resources\User\SubscriberListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnyProjectResource extends JsonResource
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
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'requireForTeamTags' => $this->getRequireForTeamTags(),
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
                'country' => new CountryResource($this->country()->first())
            ],
            'hasInBookmarks' => $this->hasInBookmarks($this->id),
            'hasInFavorites' => $this->hasInFavorites($this->id),
            'favoritesCount' => $this->favoritesCount($this->id),
            'hasInComplaints' => $this->hasInComplaints($this->id),
            'comments' => $this->getCommentsCount(),
            'isSigned' => $this->hasInSubscribersProject(findAuthUser()?->id, $this?->id),
            'team' => SubscriberListResource::collection(getSubscribersWithoutAuthor($this)),
            'type' => 'project'
        ];
    }
}
