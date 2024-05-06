<?php
namespace App\Listeners\Comment;

use App\Entity\Comment\Comment;
use App\Events\Comment\CommentEvent;
use App\Notifications\Comment\PostedComment;

class CommentEventListener
{
    public function handle(CommentEvent $event)
    {
        switch ($event->type) {
            case Comment::POST_COMMENT:
                $event->to->notify(new PostedComment($event->comment));
                break;
            case Comment::REPLY_TO_COMMENT:
                $event->to->notify(new \App\Notifications\Comment\AnsweredComment($event->comment));
                $event->to->notify(new \App\Notifications\Comment\Mail\AnsweredComment($event->comment));
                break;
            case Comment::DELETE_COMMENT:
                break;
        }
    }
}
