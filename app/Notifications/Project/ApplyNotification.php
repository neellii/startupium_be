<?php

namespace App\Notifications\Project;

use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use App\Entity\Project\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ApplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $project;
    private $subscriber;
    public function __construct(Project $project, User $subscriber)
    {
        $this->project = $project;
        $this->subscriber = $subscriber;
    }

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        return [
            'type' => Project::PROJECT_APPLY,
            'dataId' => $this->project->id,
            'title' => $this->project->title,
            'dataSlug' => $this->project->slug,
            'author' => [
                'id' => $this->subscriber->id,
                'firstname' => $this->subscriber->firstname,
                'lastname' => lastnameFormat($this->subscriber->lastname),
                'isOnline' => $this->subscriber->isOnline(),
                'lastOnlineAt' => $this->subscriber->last_online_at,
                'avatarUrl' => $this->subscriber->avatar ? $this->subscriber->avatar->url : null,
            ],
            'createdAt' => now()
        ];
    }
}
