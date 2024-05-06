<?php
namespace App\Notifications\Project;

use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use App\Entity\Project\Project;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Resources\Notification\ProjectNotificationResource;

class AddToFavorite extends Notification implements ShouldQueue
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
        if ($notifiable->notificationSettings()->get()->first()?->showLikes
            && $notifiable->id !== $this->user->id) {
            return ['database'];
        }
        return [];
    }

    public function toArray()
    {
        return [
            'type' => Project::PROJECT_TO_FAVORITES,
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
