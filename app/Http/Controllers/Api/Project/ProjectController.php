<?php
namespace App\Http\Controllers\Api\Project;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\AnyProjectResource;
use App\Http\Resources\Project\MetaAnyProjectResource;
use App\Http\Resources\Projects\ActiveProjectListResource;
use App\Http\Resources\Projects\PopularProjectsIdsResource;
use App\Http\Resources\Projects\ProjectDetailResource;
use App\UseCases\Projects\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->middleware("auth.pagination");
        $this->projectService = $projectService;
    }

    /**
     * @OA\Get(
     *      path="/projects?isSorted={isSorted}",
     *      tags={"Projects"},
     *      description="Get Projects list",
     *      summary="Список активных и на модерации или сортированных по популярности проектов",
     *      operationId="GetProjects",
     *     @OA\Parameter(
     *          name="isSorted",
     *          description="Sort or not projects",
     *          required=false,
     *          example=false,
     *          in="path",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     )
     */

    // вывод проектов на главной странице (активные или на модерации - пока)
    // или сортированные по популярности (кол-ву комментариев)
    public function getProjects(Request $request): JsonResponse
    {
        if ($request['isSorted'] === 'true') {
            $projects = $this->projectService->getProjectsSortedByCommentsCount();
        } else {
            $projects = $this->projectService->getProjects();
        }

        return Response::HTTP_OK(ActiveProjectListResource::collection($projects));
    }

    /**
     * @OA\Get(
     *      path="/projects/{id}",
     *      operationId="anyProject",
     *      tags={"Projects"},
     *      description="Any user project",
     *      security={{"apiAuth":{}}},
     *      summary="Активный или на модерации проект, любого активного пользователя. После авторизации выводит любой проект авторизованного пользвателя",
     *     @OA\Parameter(
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
     *     ),
     */
    // проект на странице {host}/projects/slug (любой активный или на модерации - пока)
    // или собственный прект авторизованного пользователя любой
    public function getAnyProject(string $slug): JsonResponse
    {
        $authUser = findAuthUser();
        $project = $this->projectService->findAnyProject($slug, $authUser);
        if ($authUser && $project->user?->id === $authUser?->id) {
            return Response::HTTP_OK(new ProjectDetailResource($project));
        }

        return Response::HTTP_OK(new AnyProjectResource($project));
    }

    public function getAnyMetaProject(string $slug): JsonResponse
    {
        $project = $this->projectService->findAnyProject($slug, null);
        return Response::HTTP_OK(new MetaAnyProjectResource($project));
    }

    public function popularProjectsIds() {
        $projects = $this->projectService->getPopularProjects();
        return Response::HTTP_OK(PopularProjectsIdsResource::collection($projects));
    }
}
