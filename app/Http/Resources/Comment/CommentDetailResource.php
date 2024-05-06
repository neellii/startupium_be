<?php
namespace App\Http\Resources\Comment;

use App\Entity\Comment\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentDetailResource extends JsonResource
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
            'project' => [
                'id' => $this->project?->id,
                'title' => $this->project?->title
            ],
            'replyComment' => $reply ? [
                'id' => $reply->id,
                'author' => $this->getAuthor(),
            ] : null,
            'totalReplies' => $parent?->id ?
                $parent->children()->count() :
                 $this->children()->count(),
            'parent' => $parent ? [
                'id' => $parent->id,
                'author' => $this->getAuthor(),
            ] : null,
            'total' => $this->project?->getCommentsCount(),
            'hasInComplaints' => $this->hasInComplaints($this->id),
        ];
    }
}
