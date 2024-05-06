<?php
namespace App\Events\Comment;

use App\Entity\Comment\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent
{
    use Dispatchable, SerializesModels;
    public $comment;
    public $to;
    public $type;

    public function __construct(Comment $comment, string $type)
    {
        $this->to = $comment->project->user;
        $this->comment = $comment;
        $this->type = $type;
    }
}
