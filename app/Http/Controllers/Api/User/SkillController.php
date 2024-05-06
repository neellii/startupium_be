<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\StoreOrUpdateRequest;
use App\Http\Resources\User\SkillListResource;
use App\UseCases\Skill\SkillService;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    private $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * @OA\Post(
     *      path="/user/skills",
     *      operationId="createOrUpdateUserSkills",
     *      tags={"User"},
     *      description="Create or update user skills",
     *      security={{"apiAuth":{}}},
     *      summary="Обновляем или создаем навыки, которыми владеет пользователь",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user technologies",
     *          @OA\JsonContent(
     *              @OA\Property(property="skills", example="[]")
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
    // создание или обновление навыков, которыми вледеет пользователь
    public function createOrUpdateSkills(StoreOrUpdateRequest $request): JsonResponse
    {
        $skills = $this->skillService->createOrUpdate($request);
        return Response::HTTP_CREATED(SkillListResource::collection($skills));
    }

   /**
     * @OA\Get(
     *      path="/skills/{id}",
     *      operationId="userSkills",
     *      tags={"User"},
     *      description="Any user skills",
     *      security={{"apiAuth":{}}},
     *      summary="Астивные Навыки пользователя. После авторизации отдает все навыки (на модерации и активные)",
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
    // навыки которыми владеет пользователь
    public function getAnyUserSkills(string $userId): JsonResponse
    {
        $skills = $this->skillService->userSkills($userId);
        return Response::HTTP_OK(SkillListResource::collection($skills));
    }
}
