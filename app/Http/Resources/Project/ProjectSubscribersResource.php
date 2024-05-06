<?php
namespace App\Http\Resources\Project;

use App\Entity\RequireTeam\RequireTeam;
use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSubscribersResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        $id = $this->pivot?->require_team_id;
        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'avatarUrl' => $this->getAvatarUrl(),
            'last_online_at' => $this->last_online_at,
            'isOnline' => $this->isOnline()
        ];
        if ($id) {
            $user['position'] = RequireTeam::query()->where('id', 'like', $this->pivot?->require_team_id ?? "")?->first()?->title;
            $user['applicationAcceptedAt'] = $this->pivot?->subscribed_at;
            $user['roleInTeam'] = $this->getRoleById($this->pivot?->role_id);
        }
        return $user;
    }
}
