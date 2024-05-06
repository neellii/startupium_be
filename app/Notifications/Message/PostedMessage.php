<?php
namespace App\Notifications\Message;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostedMessage extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;
    private $userFrom;

    public function __construct(Message $message, User $userFrom)
    {
        $this->message = $message;
        $this->userFrom = $userFrom;
    }

    public function via($notifiable)
    {
        if ($notifiable->notificationSettings()->get()->first()?->showMessages) {
            return ['database'];
        }
        return [];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => Message::POST_MESSAGE,
            'id' => $this->message->id,
            'author' => [
                'id' => $this->userFrom->id,
                'firstname' => $this->userFrom->firstname,
                'lastname' => lastnameFormat($this->userFrom->lastname),
                'avatarUrl' => $this->userFrom->avatar ? $this->userFrom->avatar->url : null,
                'isOnline' => $this->userFrom->isOnline(),
                'lastOnlineAt' => $this->userFrom->last_online_at,
            ],
            'createdAt' => now()
        ];
    }
}
