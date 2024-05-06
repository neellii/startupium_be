<?php
namespace App\Events\User;

use App\Entity\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserHaveBeenAddedToTheChatEvent implements ShouldBroadcast // не используется
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $contact;
    private $user;

    public function __construct(User $contact, User $user)
    {
        $this->contact = $contact;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('added.to.the.chat.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'contact' => [
                'id' => $this->contact->id,
                'firstname' => $this->contact->firstname,
                'lastname' => $this->contact->lastname,
                'avatar' => $this->contact->avatar ? $this->contact->avatar->url : null,
                'isOnline' => $this->contact->isOnline(),
                'last_online_at' => $this->contact->last_online_at,
            ]
        ];
    }
}
