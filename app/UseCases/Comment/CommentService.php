<?php
namespace App\UseCases\Comment;

use App\Entity\Comment\Comment;
use App\Events\Comment\CommentEvent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CommentService
{
    // основные комментарии
    public function getComments(string $projectId): LengthAwarePaginator
    {
        $project = findProjectBySlug($projectId);
        return $project->comments()
            ->whereNull('child_id')
            ->orderBy('created_at')
            ->paginate(config('constants.comments_per_page'));
    }

    // ответы на комментарии
    public function getReplies(string $commentId): LengthAwarePaginator
    {
        $comment = findComment($commentId);
        return $comment->children()
            ->orderBy('created_at')
            ->paginate(config('constants.replies_per_page'));
    }

    // создаем комментарий
    public function create(Request $request): Comment
    {
        $project = findProjectBySlug($request['slug']);
        return DB::transaction(function () use ($request, $project) {
            $comment = new Comment();

            $comment->user()->associate(authUser());
            $comment->project()->associate($project);

            $comment->comment = $request->message;

            $comment->save();

            event(new CommentEvent($comment, Comment::POST_COMMENT));
            return $comment;
        });
    }

    public function reply(Request $request, string $commentId): Comment
    {
        $comment = findComment($commentId);
        return DB::transaction(function () use ($request, $comment) {
            $reply = new Comment();

            $reply->user()->associate(authUser());
            $reply->project()->associate($comment->project);
            $parent = $comment->getParentOrNull();
            // выстраиваем цепочку так, чтобы родитель был всегда
            // основной комментарий - $comment
            if ($parent) {
                $reply->parent()->associate($parent);
            } else {
                $reply->parent()->associate($comment);
            }
            // чтобы знать кто ответил на твой комментарий
            $reply->reply()->associate($comment);

            $reply->comment = $request->message;

            $reply->save();

            event(new CommentEvent($reply, Comment::REPLY_TO_COMMENT));

            return $reply;
        });
    }

    public function remove(string $commentId): Comment
    {
        $currentComment = findAuthComment($commentId);
        $comments = $currentComment->children()->get();
        return DB::transaction(function () use ($comments, $currentComment) {
            foreach ($comments as $comment) {
                $comment->delete();
            }
            $currentComment->delete();
            return $currentComment;
        });
        //event(new CommentEvent($comment, Comment::DELETED));
    }

    public function update(Request $request, string $commentId): Comment
    {
        $comment = findAuthComment($commentId);
        $comment->update(['comment' => $request['message']]);
        return $comment;
    }

    public function get(string $commentId): Comment
    {
        return findAuthComment($commentId);
    }
}
