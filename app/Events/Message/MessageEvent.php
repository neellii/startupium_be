<?php
namespace App\Events\Message;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent
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
}
