<?php

namespace App\Http\Controllers\Api\Comment;

use DomainException;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\UseCases\Comment\BlogCommentService;
use App\Http\Requests\Comment\CreateBlogCommentReplyRequest;
use App\Http\Requests\Comment\CreateBlogCommentRequest;
use App\Http\Resources\Comment\BlogCommentDetailResource;
use App\Http\Requests\Comment\DeleteBlogCommentRequest;
use App\Http\Resources\Comment\BlogCommentListResource;

class BlogCommentController extends Controller
{
    private $blogCommentService;
    public function __construct(BlogCommentService $blogCommentService)
    {
        $this->blogCommentService = $blogCommentService;
    }

    // основные коментарии
    public function getComments(string $blog_slug): JsonResponse
    {
        $comments = $this->blogCommentService->getComments($blog_slug);
        return Response::HTTP_OK(BlogCommentListResource::collection($comments));
    }

    // ответы к коментарию
    public function getReplies(string $commentId): JsonResponse
    {
        $comments = $this->blogCommentService->getReplies($commentId);
        return Response::HTTP_OK(BlogCommentListResource::collection($comments));
    }

    // кол-во комментариев к блогу
    public function getCommentsCount(string $blog_slug)
    {
        $blog = findBlogBySlug($blog_slug);
        return Response::HTTP_OK([
            'total' => $blog->getCommentsCount(),
        ]);
    }

    // создать комментарий (родителя/основной)
    public function createComment(CreateBlogCommentRequest $request, $blog_slug): JsonResponse
    {
        if ($request['slug'] !== $blog_slug) {
            throw new DomainException(config('constants.content_not_found'));
        }
        $comment = $this->blogCommentService->create($request['message'], $request['slug']);
        return Response::HTTP_CREATED(new BlogCommentDetailResource($comment));
    }

    // обновить комментарий
    public function updateComment(CreateBlogCommentReplyRequest $request, string $commentId): JsonResponse
    {
        if ($commentId !== strval($request['commentId'])) {
            throw new DomainException(config('constants.content_not_found'));
        }
        $comment = $this->blogCommentService->update($request['message'], $commentId);
        return Response::HTTP_OK(new BlogCommentDetailResource($comment));
    }

    // удалить комментарий
    public function deleteComment(DeleteBlogCommentRequest $request, string $commentId): JsonResponse
    {
        if ($commentId !== strval($request['commentId'])) {
            throw new DomainException(config('constants.content_not_found'));
        }
        $comment = $this->blogCommentService->remove($commentId);
        return Response::HTTP_OK(new BlogCommentDetailResource($comment));
    }

    // создать ответ на комментарий
    public function createReply(CreateBlogCommentReplyRequest $request, string $commentId): JsonResponse
    {
        if ($commentId !== strval($request['commentId'])) {
            throw new DomainException(config('constants.content_not_found'));
        }
        $comment = $this->blogCommentService->reply($request['message'], $commentId);
        return Response::HTTP_CREATED(new BlogCommentDetailResource($comment));
    }
}
