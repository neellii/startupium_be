<?php
namespace App\Notifications\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RemoveFromBookmark extends Notification implements ShouldQueue
{
    use Queueable;
    private $project;
    private $user;

    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        if ($notifiable->notificationSettings()->get()->first()?->showBookmarks
            && $notifiable->id !== $this->user->id) {
            return ['database'];
        }
        return [];
    }

    public function toArray()
    {
        return [
            'type' => Project::PROJECT_FROM_BOOKMARKS,
            'dataId' => $this->project->id,
            'title' => $this->project->title,
            'dataSlug' => $this->project->slug,
            'author' => [
                'id' => $this->user->id,
                'firstname' => $this->user->firstname,
                'lastname' => lastnameFormat($this->user->lastname),
                'isOnline' => $this->user->isOnline(),
                'lastOnlineAt' => $this->user->last_online_at,
                'avatarUrl' => $this->user->avatar ? $this->user->avatar->url : null,
            ],
            'createdAt' => now()
        ];
    }
}
