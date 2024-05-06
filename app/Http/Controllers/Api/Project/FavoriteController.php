<?php
namespace App\Http\Controllers\Api\Project;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Centrifugo\ProjectToResource;
use App\Http\Resources\Project\UpdateProjectResource;
use App\UseCases\Centrifugo\CentrifugoService;
use App\UseCases\Projects\FavoriteService;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    private $service;
    private $centrifugoService;
    public function __construct(FavoriteService $service, CentrifugoService $centrifugoService)
    {
        $this->service = $service;
        $this->centrifugoService = $centrifugoService;
    }

    /**
     * @OA\Get(
     *   path="/favorites/{id}/favorite",
     *   tags={"User favorites"},
     *   summary="Содержится ли проект в понравившихся",
     *   operationId="hasInFavorites",
     *   description="Has project in favorites",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="id проекта",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *           type="string"
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *           @OA\Property(property="hasInFavorites", type="boolean", example="false"),
     *      ),
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=401,
     *          description="Unauthorized",
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
    // // содержится ли проект в понравившихся
    public function hasInFavorites(string $projectId): JsonResponse
    {
        $favorites = $this->service->favorites($projectId);
        return Response::HTTP_OK(['hasInFavorites' => $favorites->contains($projectId)]);
    }

    /**
     * @OA\Get(
     *   path="/favorites/{id}/total",
     *   tags={"User favorites"},
     *   summary="Кол-во лайков для проекта",
     *   operationId="favoritesTotal",
     *   description="Total favorites",
     *   @OA\Parameter(
     *      name="id",
     *      description="id проекта",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *           type="string"
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *           @OA\Property(property="total", type="number", example="3"),
     *      ),
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    // кол-во лайков для проекта
    public function getProjectFavoritesCount(string $projectId): JsonResponse
    {
        $total = $this->service->projectFavoritesCount($projectId);
        return Response::HTTP_OK(['total' => $total]);
    }

    /**
     * @OA\Post(
     *   path="/favorites/{id}/favorite",
     *   tags={"User favorites"},
     *   summary="Добавить проект в понравившиеся",
     *   operationId="addToFavorites",
     *   description="Add project to favorites",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="id проекта",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *           type="string"
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *           @OA\Property(property="total", type="number", example="4"),
     *           @OA\Property(property="successfullyRemoved", type="boolean", example="true"),
     *      ),
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
    // добавить проект в понравившийся
    public function add(string $projectId): JsonResponse
    {
        $project = $this->service->add(authUser(), $projectId);
        $this->centrifugoService->notifyProjectToFavorites($project);
        return Response::HTTP_OK(new UpdateProjectResource($project));
    }

     /**
     * @OA\Delete(
     *   path="/favorites/{id}/favorite",
     *   tags={"User favorites"},
     *   summary="Удалить проект из понравившихся",
     *   operationId="deleteFromFavorites",
     *   description="Delete project from favorites",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="id проекта",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *           type="string"
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *           @OA\Property(property="total", type="number", example="4"),
     *           @OA\Property(property="successfullyRemoved", type="boolean", example="true"),
     *      ),
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
    // удалить проект из понравившихся
    public function remove(string $projectId): JsonResponse
    {
        $project = $this->service->remove(authUser(), $projectId);
        //$this->centrifugoService->notifyProjectFromFavorites($project);
        return Response::HTTP_OK(new UpdateProjectResource($project));
    }
}
