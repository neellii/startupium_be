<?php
namespace App\Http\Controllers\Api\Project;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\UpdateProjectResource;
use App\Http\Resources\Projects\ActiveProjectListResource;
use App\UseCases\Centrifugo\CentrifugoService;
use App\UseCases\Projects\BookmarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkController extends Controller
{
    private $service;
    private $centrifugoService;

    public function __construct(BookmarkService $service, CentrifugoService $centrifugoService)
    {
        $this->service = $service;
        $this->centrifugoService = $centrifugoService;
    }

    /**
     * @OA\Post(
     *   path="/bookmarks/{id}/bookmark",
     *   tags={"User bookmarks"},
     *   summary="Добавить проект в закладки",
     *   operationId="addToBookmarks",
     *   description="Add project to bookmarks",
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
     *           @OA\Property(property="projectId", type="string", format="string", example="4"),
     *           @OA\Property(property="successfullyAdded", type="boolean", example="true"),
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
    // добавить проект в закладки
    public function add(string $projectId): JsonResponse
    {
        $project = $this->service->add(authUser(), $projectId);
        $this->centrifugoService->notifyProjectToBookmarks($project);
        return Response::HTTP_OK(new UpdateProjectResource($project));
    }

        /**
     * @OA\Delete(
     *   path="/bookmarks/{id}/bookmark",
     *   tags={"User bookmarks"},
     *   summary="Удалить проект из закладок",
     *   operationId="deleteFromBookmarks",
     *   description="Delete project from bookmarks",
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
     *           @OA\Property(property="projectId", type="string", format="string", example="4"),
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
    // удалить проект из закладок
    public function remove(string $projectId): JsonResponse
    {
        $project = $this->service->remove(authUser(), $projectId);
        //$this->centrifugoService->notifyProjectFromBookmarks($project);
        return Response::HTTP_OK(new UpdateProjectResource($project));
    }

    /**
     * @OA\Get(
     *   path="/bookmarks/{id}/bookmark",
     *   tags={"User bookmarks"},
     *   summary="Содержится ли проект в закладках",
     *   operationId="hasInBookmarks",
     *   description="Has project in bookmarks",
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
     *           @OA\Property(property="hasInBookmarks", type="boolean", example="false"),
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
    // содержится ли проект в закладках
    public function hasInBookmarks(string $projectId): JsonResponse
    {
        $bookmarks = $this->service->bookmarks($projectId);
        return Response::HTTP_OK(['hasInBookmarks' => $bookmarks->contains($projectId)]);
    }

    /**
     * @OA\Get(
     *   path="/user/bookmarks",
     *   tags={"User bookmarks"},
     *   summary="Проекты в закладках авторизованного пользователя",
     *   operationId="userBookmarks",
     *   description="User bookmarks",
     *   security={{"apiAuth":{}}},
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
    // все закладки авторизованного пользователя [...ids] или [] на странице {host}/my-bookmarks
    public function getUserBookmarks(): JsonResource
    {
        $bookmarks = $this->service->allBookmarks();
        return ActiveProjectListResource::collection($bookmarks);
    }
}
