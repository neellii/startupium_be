<?php
namespace App\UseCases\Comment;

use App\Entity\Comment\BlogComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogCommentService
{
    // основные комментарии
    public function getComments(string $blogSlug): LengthAwarePaginator
    {
        $blog = findBlogBySlug($blogSlug);
        return $blog->comments()
            ->whereNull('child_id')
            ->orderBy('created_at')
            ->paginate(config('constants.comments_per_page'));
    }

    // ответы на комментарии
    public function getReplies(string $commentId): LengthAwarePaginator
    {
        $comment = findBlogComment($commentId);
        return $comment->children()
            ->orderBy('created_at')
            ->paginate(config('constants.replies_per_page'));
    }

    // создаем комментарий
    public function create(string $message, $blog_slug): BlogComment
    {
        $blog = findBlogBySlug($blog_slug);
        return DB::transaction(function () use ($message, $blog) {
            $comment = new BlogComment();

            $comment->user()->associate(authUser());
            $comment->blog()->associate($blog);

            $comment->comment = $message;

            $comment->save();

            return $comment;
        });
    }

    public function reply(string $message, string $commentId): BlogComment
    {
        $comment = findBlogComment($commentId);
        return DB::transaction(function () use ($message, $comment) {
            $reply = new BlogComment();

            $reply->user()->associate(authUser());
            $reply->blog()->associate($comment->blog);
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

            $reply->comment = $message;

            $reply->save();

            return $reply;
        });
    }

    public function remove(string $commentId): BlogComment
    {
        $currentComment = findAuthBlogComment($commentId);
        $comments = $currentComment->children()->get();
        return DB::transaction(function () use ($comments, $currentComment) {
            foreach ($comments as $comment) {
                $comment->delete();
            }
            $currentComment->delete();
            return $currentComment;
        });
    }

    public function update(string $message, string $commentId): BlogComment
    {
        $comment = findAuthBlogComment($commentId);
        $comment->update(['comment' => $message]);
        return $comment;
    }

    public function get(string $commentId): BlogComment
    {
        return findAuthBlogComment($commentId);
    }
}
