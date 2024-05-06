<?php
namespace App\Events\Message;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemoveMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $lastMessage;
    public $from;
    public $to;

    public function __construct(Message $message, Message $lastMessage, User $to, User $from)
    {
        $this->message = $message;
        $this->lastMessage = $lastMessage;
        $this->to = $to;
        $this->from = $from;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('messages.' . $this->message->to * $this->message->from);
    }

    public function broadcastWith()
    {
        return [
            'data' => [
                'removeMessage' => $this->message,
                'lastMessage' => $this->lastMessage
            ]
        ];
    }
}
