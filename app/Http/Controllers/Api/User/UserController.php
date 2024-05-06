<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\AnyUserResource;
use App\Http\Resources\User\UserListResource;
use App\UseCases\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware("auth.pagination");
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsers",
     *      tags={"User"},
     *      description="Get active users",
     *      summary="Список активных пользователей",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     )
     *     ),
     */
    // активные пользователи
    public function getUsers(): JsonResponse
    {
        $users = $this->userService->getActiveUsers();
        return Response::HTTP_OK(UserListResource::collection($users));
    }

    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="anyActiveUser",
     *      tags={"User"},
     *      description="Any active user",
     *      summary="Любой активный пользователь",
     *     @OA\Parameter(
     *          name="id",
     *          description="id пользователя",
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
     *              @OA\Property(property="message", type="string", example="Пользователь не найден."),
     *          ),
     *       ),
     *     )
     *     ),
     */
    // любой пользователь активный пользователь
    public function getAnyUser(string $id): JsonResponse
    {
        $user = $this->userService->getAnyActiveUser($id);
        return Response::HTTP_OK(new AnyUserResource($user));
    }

    public function getAvatar(string $dir, string $filename): HttpResponse
    {
        return $this->userService->getAvatar($dir, $filename);
    }
}
