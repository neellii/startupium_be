<?php
namespace App\Http\Resources\Comment;

use App\Entity\Comment\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Comment $this */
        $parent = $this->getParentOrNull();
        $reply = $this->getReplyOrNull();
        return [
            'id' => $this->id,
            'message' => $this->getCustomMessage(),
            'createdAt' => $this->created_at,
            'isDeleted' => $this->isDeleted(),
            'author' => $this->getAuthor(),
            'replyComment' => $reply ? [
                'id' => $reply->id,
                'author' => $this->getAuthor(),
            ] : null,
            'totalReplies' => $this->children()->count(),
            'hasInComplaints' => $this->hasInComplaints($this->id),
            'parent' => $parent ? [
                'id' => $parent->id,
                'author' => $this->getAuthor(),
            ] : null
        ];
    }
}
