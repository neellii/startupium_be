<?php
namespace App\Listeners\Message;

use App\Events\Message\MessageEvent;

class MessageEventListener
{
    public function handle(MessageEvent $event)
    {
        $event->to->notify(new \App\Notifications\Message\PostedMessage($event->message, $event->from));
        $event->to->notify(new \App\Notifications\Message\Mail\PostedMessage($event->message, $event->from));
    }
}
