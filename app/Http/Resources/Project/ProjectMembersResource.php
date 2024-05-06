<?php

namespace App\Http\Resources\Project;

use App\Entity\Project\Project;
use App\Entity\RequireTeam\RequireTeam;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectMembersResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        $id = $this->pivot?->require_team_id;
        $project_id = $this->pivot?->project_id;
        $project = Project::query()->where('id', 'like', $project_id)->first();
        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'avatarUrl' => $this->getAvatarUrl(),
            'last_online_at' => $this->last_online_at,
            'isOnline' => $this->isOnline(),
            'roleInTeam' => $this->getRoleById($this->pivot?->role_id),
            'permissions' => $this->getPermissionsByRoleId($this->pivot?->role_id),
        ];
        if ($id) {
            $user['position'] = RequireTeam::query()->where('id', 'like', $this->pivot?->require_team_id ?? "")?->first()?->title;
            $user['applicationAcceptedAt'] = $this->pivot?->subscribed_at;
        }
        if ($project_id) {
            $user['projectSlug'] = $project?->slug;
            $user['subscribedTo'] = $project?->title;
        }
        return $user;
    }
}
