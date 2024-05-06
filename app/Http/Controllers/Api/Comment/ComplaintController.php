<?php

namespace App\Http\Controllers\Api\Comment;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateComplainRequest;
use App\Http\Resources\Comment\CommentDetailResource;
use App\UseCases\Comment\ComplaintsService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    private $service;

    public function __construct(ComplaintsService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     ** path="/comments/{id}/report",
     *   tags={"Comments"},
     *   summary="Жалоба на комментарий",
     *   operationId="reportComment",
     *   description="User create new report",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="id Комментария",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="string"
     *       )
     *   ),
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass report reason",
     *      @OA\JsonContent(
     *         required={"reason"},
     *         @OA\Property(property="reason", type="string", format="string", example="Bad comment"),
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
    // Добавить в жалобы
    public function add(CreateComplainRequest $request, string $commentId)
    {
        $comment = $this->service->add($request, $commentId);
        return Response::HTTP_CREATED(new CommentDetailResource($comment));
    }

    // НЕ ИСПОЛЬЗУЕТСЯ
    // удалить жалобу на комментарий
    public function remove(Request $request, string $commentId)
    {
        $this->service->remove($request, $commentId);
        return Response::HTTP_OK([]);
    }
}
