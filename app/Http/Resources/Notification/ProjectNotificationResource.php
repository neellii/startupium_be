<?php

namespace App\Http\Resources\Notification;

use App\Entity\User\User;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectNotificationResource extends JsonResource
{
    private $project;
    private $author;
    private $type;
    public function __construct(Project $project, User $author, string $type)
    {
        $this->project = $project;
        $this->author = $author;
        $this->type = $type;
    }
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => $this->type,
                'dataId' => $this->project->id,
                'dataSlug' => $this->project->slug,
                'title' => $this->project->title,
                'author' => [
                    'id' => $this->author->id,
                    'firstname' => $this->author->firstname,
                    'lastname' => lastnameFormat($this->author->lastname),
                    'isOnline' => $this->author->isOnline(),
                    'lastOnlineAt' => $this->author->last_online_at,
                    'avatarUrl' => $this->author->avatar ? $this->author->avatar->url : null,
                ],
                'createdAt' => now()
            ],
            'readAt' => null,
            'createdAt' => now()
        ];
    }
}
