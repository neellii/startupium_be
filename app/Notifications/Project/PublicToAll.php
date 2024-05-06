<?php
namespace App\Notifications\Project;

use App\Entity\Project\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PublicToAll extends Notification implements ShouldQueue
{
    use Queueable;
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'publicToAll',
            'dataId' => $this->project->id,
            'title' => $this->project->title,
            'dataSlug' => $this->project->slug,
            'author' => [
                'id' => $this->project->user->id,
                'firstname' => $this->project->user->firstname,
                'lastname' => lastnameFormat($this->project->user->lastname),
                'avatarUrl' => $this->project->user->avatar ? $this->project->user->avatar->url : null,
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
        return 'publicToAll';
    }
}
