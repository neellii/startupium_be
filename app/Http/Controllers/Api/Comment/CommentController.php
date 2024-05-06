<?php
namespace App\Http\Controllers\Api\Comment;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateRequest;
use App\Http\Requests\Comment\ReplyRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Http\Resources\Comment\CommentDetailResource;
use App\Http\Resources\Comment\CommentListResource;
use App\UseCases\Centrifugo\CentrifugoService;
use App\UseCases\Comment\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private $service;
    private $centrifugoService;

    public function __construct(CommentService $service, CentrifugoService $centrifugoService)
    {
        $this->service = $service;
        $this->centrifugoService = $centrifugoService;
    }

    /**
     * @OA\Post(
     ** path="/comment",
     *   tags={"Comments"},
     *   summary="Новый комментарий",
     *   operationId="newComment",
     *   description="User create new comment",
     *   security={{"apiAuth":{}}},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass comment data",
     *      @OA\JsonContent(
     *         required={"projectId","message"},
     *         @OA\Property(property="projectId", type="string", format="string", example="34"),
     *         @OA\Property(property="message", type="string", format="string", example="Nice project"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Created",
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    // Сохранить новый комментарий
    public function createComment(CreateRequest $request): JsonResponse
    {
        $comment = $this->service->create($request);
        $this->centrifugoService->notifyPostComment($comment);
        return Response::HTTP_CREATED(new CommentDetailResource($comment));
    }

    // не используется
    // Получить комментарий для редактирование
    public function edit(string $commentId): JsonResponse
    {
        $comment = $this->service->get($commentId);
        return Response::HTTP_OK(new CommentDetailResource($comment));
    }

    /**
     * @OA\Put(
     ** path="/comment/{id}",
     *   tags={"Comments"},
     *   summary="Изменить свой комментарий",
     *   operationId="updateComment",
     *   description="User update comment",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *          name="id",
     *          description="id Комментария",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass comment data",
     *      @OA\JsonContent(
     *         required={"message"},
     *         @OA\Property(property="message", type="string", format="string", example="Nice comment"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    // Обновить комментарий
    public function updateComment(UpdateRequest $request, string $commentId): JsonResponse
    {
        $comment = $this->service->update($request, $commentId);
        return Response::HTTP_OK(new CommentDetailResource($comment));
    }

    /**
     * @OA\Delete(
     ** path="/comment/{id}",
     *   tags={"Comments"},
     *   summary="Удалить свой комментарий",
     *   operationId="deleteComment",
     *   description="User delete comment",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *          name="id",
     *          description="id Комментария",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    // Удалить комментарий
    public function deleteComment(string $commentId): JsonResponse
    {
        $comment = $this->service->remove($commentId);
        return Response::HTTP_OK(new CommentDetailResource($comment));
    }

    /**
     * @OA\Post(
     ** path="/comment/{id}/reply",
     *   tags={"Comments"},
     *   summary="Новый ответ на комментарий",
     *   operationId="newReply",
     *   description="User create new reply",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *          name="id",
     *          description="id Комментария",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass reply data",
     *      @OA\JsonContent(
     *         required={"message"},
     *         @OA\Property(property="message", type="string", format="string", example="Nice project"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Created",
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    // Ответить на комментарий
    public function createReply(ReplyRequest $request, string $commentId): JsonResponse
    {
        $reply = $this->service->reply($request, $commentId);
        $this->centrifugoService->notifyReplyToComment($reply);
        return Response::HTTP_CREATED(new CommentDetailResource($reply));
    }

    /**
     * @OA\Get(
     *      path="/comments/{id}",
     *      operationId="anyProjectComments",
     *      tags={"Comments"},
     *      description="Main comments to project",
     *      summary="Основные Комментарии к проекту",
     *      @OA\Parameter(
     *          name="id",
     *          description="id проекта",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Проект не найден."),
     *          ),
     *       ),
     *     )
     */
    // Основные Комментарии к проекту
    public function getComments($projectId): JsonResponse
    {
        $comments = $this->service->getComments($projectId);
        return Response::HTTP_OK(CommentListResource::collection($comments));
    }

      /**
     * @OA\Get(
     *      path="/replies/{id}",
     *      operationId="anyCommentReplies",
     *      tags={"Comments"},
     *      description="Replies to comment",
     *      summary="Ответы на комментарий",
     *      @OA\Parameter(
     *          name="id",
     *          description="id Комментария",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Комментарий не найден или был удален."),
     *          ),
     *       ),
     *     )
     */
    // Ответы на комментарий
    public function getReplies($commentId): JsonResponse
    {
        $replies = $this->service->getReplies($commentId);
        return Response::HTTP_OK(CommentListResource::collection($replies));
    }

    /**
     * @OA\Get(
     *      path="/comments/{id}/count",
     *      operationId="totalComments",
     *      tags={"Comments"},
     *      description="Comments count",
     *      summary="Получение количества коментариев по id проекта",
     *      @OA\Parameter(
     *          name="id",
     *          description="id проекта",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="total", type="int", example="10"),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Проект не найден."),
     *          ),
     *       ),
     *     )
     */
    // кол-во комментариев
    public function getCommentsCount($slug)
    {
        $project = findProjectBySlug($slug);
        return Response::HTTP_OK([
            'total' => $project->getCommentsCount(),
        ]);
    }
}
