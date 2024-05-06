<?php
namespace App\Http\Controllers\Api\User;

use App\Entity\Project\Project;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CreateDraftRequest;
use App\Http\Requests\Project\CreateRequest;
use App\Http\Requests\Project\EditRequest;
use App\Http\Requests\Project\LeaveProjectRequest;
use App\Http\Resources\Project\DeleteProjectResource;
use App\Http\Resources\Project\ProjectModalListResource;
use App\Http\Resources\Project\UpdateProjectResource;
use App\Http\Resources\Projects\StatusProjectListResources;
use App\UseCases\Projects\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *   path="/user/projects",
     *   tags={"User projects"},
     *   summary="Проекты авторизованного пользователя",
     *   operationId="userProjects",
     *   description="User projects",
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
    // выводим проекты авторизованного пользователя
    public function getProfileProjects(): JsonResponse
    {
        $projects = $this->service->getProfileProjects();
        return Response::HTTP_OK(StatusProjectListResources::collection($projects));
    }

    // только активные проекты пользователя
    public function activeProjects(): JsonResponse
    {
        $projects = $this->service->getAuthUserActiveProjects();
        return Response::HTTP_OK(ProjectModalListResource::collection($projects));
    }

     /**
     * @OA\Post(
     *   path="/user/projects",
     *   tags={"User projects"},
     *   summary="создать проект авторизованного пользователя",
     *   operationId="сreateProject",
     *   description="Create user project",
     *   security={{"apiAuth":{}}},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass project data. Title - min 3 chars, Description - min 10 chars, Text - min 250 chars see text example, Tags - min one element",
     *      @OA\JsonContent(
     *         required={"title", "tags", "text", "description"},
     *         @OA\Property(property="title", type="string", format="string", example="Title"),
     *         @OA\Property(property="text", type="json", format="json", example="{'time': 1654673458215, 'blocks': [{'id': 'DqBr9aICJZ', 'data': {'text': 'eyJ0eXAiOiJKV1 QiLCJhbGciOiJSUzI1Ni J9.eyJhdWQiOiIxI iwianRpIjoiYT MzMDdiZWQyMmEyMzM3 ZDU0M2YxNWI zNWVkZm RhYjI 3ZWUyZWVhNWY0 YTAwY2RmZmM2N2E4 ZjkyN2Ji ZWY4MD ExNjhhNmUzNTRjNW NlNjEiLCJ pYXQiOjE2NT UzMjAyNzcuOTM0NDY4LCJuYmYiOjE2NTUzMjAy NzcuOTM0NDcsImV4cC I6MTY4Njg1NjI3 Ny45MTkyM  iwic3ViIjo iMzUiLCJzY29wZXM iOltdfQ.mQ8U VOwj9SDk XwvuL6bUXZ 4FTHjEolP JqOxK7j Stjpu1hWe1HIoOeRwpPQ-KbBxE1dGSOTq6DUeF__O8407WMsNhOnJgGMvkQADXXCU lVszm6gQ 8RlET0JMdET xo9GF-pCfLjIyKvedpjuD0XYjm0HCil556xOxeYpgy_xXZyvZfyRnM lLzJohN hpQeQx MuKA6PSJ7E Qj5AOXw_5VCV1eHXWNLNx5a_9Sts1OmjTQ7FvhyrPjhZlAPPx0nPnSl1NocikU_3gs7RmaiG3BoAwT5wZ-2S-_-gHBKekaaLDoJRY10y_YhcMSx_6ObRD5Nxo9kGUHvxzncE6J7vNQfkWckNnN4ZhMmKzeBKmZNDJHJfzFovU_Uxj6_z6l9SxyxhhyzY5OKe4OsfGJ-JXU1n6qVc1ZQ5zpD972P5lmHqA99qCnk5BMY9a24E86KNSQElTHUjCEvXbMWJ2Qm0yOyY0_wFvzm2xu_tttK7CP3caznRji35H8XiB68uxalClsKq5aq9LdKDBH1f2vl47ufuT2dDlymr_-1hn5pyecMIXSTOy2un6MlBO-XgzRcrHy2VTZxIggsU-0Wo_TygfvHXdrlz5RaFdJCtYbnHaFl99ZZWl4UFW7LG2WQrhzuMj7_sXE5QQ9wCa8qjF4ZZaxvNbu98y9GpagH7uzMrC-k92ho'}, 'type': 'paragraph'}], 'version': '2.23.2'}"),
     *         @OA\Property(property="description", type="string", format="string", example="Description"),
     *         @OA\Property(property="tags", type="string", format="string", example="{['php', 'java']}"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Created",
     *      @OA\JsonContent(
     *           @OA\Property(property="id", type="string", example="8"),
     *           @OA\Property(property="success", type="boolean", example="true"),
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
    // сохраняем проект
    public function createProject(CreateRequest $request): JsonResponse
    {
        $project = $this->service->create($request, Project::STATUS_MODERATION);
        return Response::HTTP_CREATED(new UpdateProjectResource($project));
    }

       /**
     * @OA\Delete(
     *   path="/user/projects/{id}",
     *   tags={"User projects"},
     *   summary="Удалить проект авторизованного пользователя",
     *   operationId="deleteProject",
     *   description="Delete user project",
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
    // удаление проекта
    public function deleteProject(string $projectId): JsonResponse
    {
        $project = $this->service->remove($projectId);
        return Response::HTTP_OK(new DeleteProjectResource($project));
    }

      /**
     * @OA\Put(
     *   path="/user/projects/{id}",
     *   tags={"User projects"},
     *   summary="Обновить проект авторизованного пользователя",
     *   operationId="updateProject",
     *   description="Update user project",
     *   security={{"apiAuth":{}}},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass project data. Title - min 3 chars, Description - min 10 chars, Text - min 250 chars see text example, Tags - min one element",
     *      @OA\JsonContent(
     *         required={"title", "tags", "text", "description"},
     *         @OA\Property(property="title", type="string", format="string", example="Title"),
     *         @OA\Property(property="text", type="json", format="json", example="{'time': 1654673458215, 'blocks': [{'id': 'DqBr9aICJZ', 'data': {'text': 'eyJ0eXAiOiJKV1 QiLCJhbGciOiJSUzI1Ni J9.eyJhdWQiOiIxI iwianRpIjoiYT MzMDdiZWQyMmEyMzM3 ZDU0M2YxNWI zNWVkZm RhYjI 3ZWUyZWVhNWY0 YTAwY2RmZmM2N2E4 ZjkyN2Ji ZWY4MD ExNjhhNmUzNTRjNW NlNjEiLCJ pYXQiOjE2NT UzMjAyNzcuOTM0NDY4LCJuYmYiOjE2NTUzMjAy NzcuOTM0NDcsImV4cC I6MTY4Njg1NjI3 Ny45MTkyM  iwic3ViIjo iMzUiLCJzY29wZXM iOltdfQ.mQ8U VOwj9SDk XwvuL6bUXZ 4FTHjEolP JqOxK7j Stjpu1hWe1HIoOeRwpPQ-KbBxE1dGSOTq6DUeF__O8407WMsNhOnJgGMvkQADXXCU lVszm6gQ 8RlET0JMdET xo9GF-pCfLjIyKvedpjuD0XYjm0HCil556xOxeYpgy_xXZyvZfyRnM lLzJohN hpQeQx MuKA6PSJ7E Qj5AOXw_5VCV1eHXWNLNx5a_9Sts1OmjTQ7FvhyrPjhZlAPPx0nPnSl1NocikU_3gs7RmaiG3BoAwT5wZ-2S-_-gHBKekaaLDoJRY10y_YhcMSx_6ObRD5Nxo9kGUHvxzncE6J7vNQfkWckNnN4ZhMmKzeBKmZNDJHJfzFovU_Uxj6_z6l9SxyxhhyzY5OKe4OsfGJ-JXU1n6qVc1ZQ5zpD972P5lmHqA99qCnk5BMY9a24E86KNSQElTHUjCEvXbMWJ2Qm0yOyY0_wFvzm2xu_tttK7CP3caznRji35H8XiB68uxalClsKq5aq9LdKDBH1f2vl47ufuT2dDlymr_-1hn5pyecMIXSTOy2un6MlBO-XgzRcrHy2VTZxIggsU-0Wo_TygfvHXdrlz5RaFdJCtYbnHaFl99ZZWl4UFW7LG2WQrhzuMj7_sXE5QQ9wCa8qjF4ZZaxvNbu98y9GpagH7uzMrC-k92ho'}, 'type': 'paragraph'}], 'version': '2.23.2'}"),
     *         @OA\Property(property="description", type="string", format="string", example="Description"),
     *         @OA\Property(property="tags", type="string", format="string", example="{['php', 'java']}"),
     *      ),
     *   ),
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
     *      response=201,
     *      description="Created",
     *      @OA\JsonContent(
     *           @OA\Property(property="id", type="string", example="8"),
     *           @OA\Property(property="success", type="boolean", example="true"),
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
    // обновление проекта
    public function updateProject(EditRequest $request, string $projectId): JsonResponse
    {
        $project = $this->service->edit($projectId, $request);
        return Response::HTTP_CREATED(new UpdateProjectResource($project));
    }

    /**
     * @OA\Put(
     *   path="/user/projects/{id}/onDraft",
     *   tags={"User projects"},
     *   summary="Перемещаем проект в черновик",
     *   operationId="projectToDraft",
     *   description="project to draft",
     *   security={{"apiAuth":{}}},
     *   @OA\Parameter(
     *          name="id",
     *          description="id проекта",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass draft data",
     *      @OA\JsonContent(
     *         required={"title"},
     *         @OA\Property(property="title", type="string", format="string", example="Title"),
     *         @OA\Property(property="text", type="json", format="json", example="{'time': 1654673458215, 'blocks': [{'id': 'DqBr9aICJZ', 'data': {'text': 'eyJ0eXAiOiJKV1 QiLCJhbGciOiJSUzI1Ni J9.eyJhdWQiOiIxI iwianRpIjoiYT MzMDdiZWQyMmEyMzM3 ZDU0M2YxNWI zNWVkZm RhYjI 3ZWUyZWVhNWY0 YTAwY2RmZmM2N2E4 ZjkyN2Ji ZWY4MD ExNjhhNmUzNTRjNW NlNjEiLCJ pYXQiOjE2NT UzMjAyNzcuOTM0NDY4LCJuYmYiOjE2NTUzMjAy NzcuOTM0NDcsImV4cC I6MTY4Njg1NjI3 Ny45MTkyM  iwic3ViIjo iMzUiLCJzY29wZXM iOltdfQ.mQ8U VOwj9SDk XwvuL6bUXZ 4FTHjEolP JqOxK7j Stjpu1hWe1HIoOeRwpPQ-KbBxE1dGSOTq6DUeF__O8407WMsNhOnJgGMvkQADXXCU lVszm6gQ 8RlET0JMdET xo9GF-pCfLjIyKvedpjuD0XYjm0HCil556xOxeYpgy_xXZyvZfyRnM lLzJohN hpQeQx MuKA6PSJ7E Qj5AOXw_5VCV1eHXWNLNx5a_9Sts1OmjTQ7FvhyrPjhZlAPPx0nPnSl1NocikU_3gs7RmaiG3BoAwT5wZ-2S-_-gHBKekaaLDoJRY10y_YhcMSx_6ObRD5Nxo9kGUHvxzncE6J7vNQfkWckNnN4ZhMmKzeBKmZNDJHJfzFovU_Uxj6_z6l9SxyxhhyzY5OKe4OsfGJ-JXU1n6qVc1ZQ5zpD972P5lmHqA99qCnk5BMY9a24E86KNSQElTHUjCEvXbMWJ2Qm0yOyY0_wFvzm2xu_tttK7CP3caznRji35H8XiB68uxalClsKq5aq9LdKDBH1f2vl47ufuT2dDlymr_-1hn5pyecMIXSTOy2un6MlBO-XgzRcrHy2VTZxIggsU-0Wo_TygfvHXdrlz5RaFdJCtYbnHaFl99ZZWl4UFW7LG2WQrhzuMj7_sXE5QQ9wCa8qjF4ZZaxvNbu98y9GpagH7uzMrC-k92ho'}, 'type': 'paragraph'}], 'version': '2.23.2'}"),
     *         @OA\Property(property="description", type="string", format="string", example="Description"),
     *         @OA\Property(property="tags", type="string", format="string", example="{['php', 'java']}"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Created",
     *      @OA\JsonContent(
     *           @OA\Property(property="id", type="string", format="string", example="4"),
     *           @OA\Property(property="success", type="boolean", example="true"),
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
    // перемещение в черновик c сохранением полей
    public function onDraft(CreateDraftRequest $request, string $projectId): JsonResponse
    {
        $project = $this->service->onDraft($request, $projectId);
        return Response::HTTP_CREATED(new UpdateProjectResource($project));
    }
    // перемещение в черновик без изменения полей кроме slug
    public function onDraftSimple(string $projectId): JsonResponse
    {
        $project = $this->service->onDraftSimple($projectId);
        return Response::HTTP_CREATED(new UpdateProjectResource($project));
    }

    // покинуть проект (передать проект подписчику)
    public function leaveProject(LeaveProjectRequest $request, string $projectId) {
        $this->service->leaveProject($request, $projectId);
        return Response::HTTP_OK(['ok' => true]);
    }

    // список из названий проектов и авторизованного пользователя
    public function titleOfProjects() {
        $auth = authUser();
        $user = [
            'slug' => "",
            'id' => $auth->id,
            'title' => trim($auth->firstname . ' ' . lastnameFormat($auth->lastname)),
        ];
        $projects = $this->service->getTitleOfProjects($auth);
        return Response::HTTP_OK(['data' => $projects->add($user)]);
    }
}
