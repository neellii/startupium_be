<?php
namespace App\Notifications\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AddToReport extends Notification
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
        if ($notifiable->notificationSettings()->get()->first()?->showReports
            && $notifiable->id !== $this->user->id) {
            return ['database', 'broadcast'];
        }
        return [];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'addToReports',
            'dataId' => $this->project->id,
            'title' => $this->project->title,
            'dataSlug' => $this->project->slug,
            'author' => [
                'id' => $this->user->id,
                'firstname' => $this->user->firstname,
                'lastname' => lastnameFormat($this->user->lastname),
                'avatarUrl' => $this->user->avatar ? $this->user->avatar->url : null,
            ],
            'createdAt' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastType()
    {
        return 'addToReports';
    }
}
