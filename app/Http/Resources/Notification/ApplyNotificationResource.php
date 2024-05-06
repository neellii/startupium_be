<?php

namespace App\Http\Resources\Notification;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplyNotificationResource extends JsonResource
{
    private $project;
    private $subscriber;
    public function __construct(Project $project, User $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->project = $project;
    }
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => Project::PROJECT_APPLY,
                'dataId' => $this->project->id,
                'dataSlug' => $this->project->slug,
                'title' => $this->project->title,
                'author' => [
                    'id' => $this->subscriber->id,
                    'firstname' => $this->subscriber->firstname,
                    'lastname' => lastnameFormat($this->subscriber->lastname),
                    'isOnline' => $this->subscriber->isOnline(),
                    'lastOnlineAt' => $this->subscriber->last_online_at,
                    'avatarUrl' => $this->subscriber->avatar ? $this->subscriber->avatar->url : null,
                ],
                'createdAt' => now()
            ],
            'readAt' => null,
            'createdAt' => now()
        ];
    }
}
