<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Technology\StoreOrUpdateRequest;
use App\Http\Resources\User\TechnologyListResource;
use App\UseCases\Technology\TechnologyService;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    private $technologyService;

    public function __construct(TechnologyService $technologyService)
    {
        $this->technologyService = $technologyService;
    }

    /**
     * @OA\Post(
     *      path="/user/technologies",
     *      operationId="createOrUpdateUserTechnologies",
     *      tags={"User"},
     *      description="Create or update user technologies",
     *      security={{"apiAuth":{}}},
     *      summary="Обновляем или создаем технологии, которыми владеет пользователь",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user technologies",
     *          @OA\JsonContent(
     *              @OA\Property(property="technologies", example="[]")
     *              ),
     *          ),
     *     @OA\Response(
     *          response=201,
     *          description="Created"
     *       ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
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
    // создание или обновление технологий, которыми вледеет пользователь
    public function createOrUpdateTechnologies(StoreOrUpdateRequest $request)
    {
        $technologies = $this->technologyService->createOrUpdate($request);
        return Response::HTTP_CREATED(TechnologyListResource::collection($technologies));
    }

    /**
     * @OA\Get(
     *      path="/technologies/{id}",
     *      operationId="userTechnologies",
     *      tags={"User"},
     *      description="Any user technologies",
     *      security={{"apiAuth":{}}},
     *      summary="Астивные Технологии, которыми владеет пользователь. После авторизации отдает все Технологии (на модерации и активные)",
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
    // технологии которыми владеет пользователь
    public function getAnyUserTechnologies(string $userId): JsonResponse
    {
        $technologies = $this->technologyService->userTechnologies($userId);
        return Response::HTTP_OK(TechnologyListResource::collection($technologies));
    }
}
