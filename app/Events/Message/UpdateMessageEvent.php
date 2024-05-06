<?php
namespace App\Events\Message;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $from;
    public $to;

    public function __construct(Message $message, User $to, User $from)
    {
        $this->message = $message;
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
            'message' => $this->message
        ];
    }
}
