<?php
namespace App\Http\Controllers\Api\Search;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ActiveProjectListResource;
use App\Http\Resources\User\UserListResource;
use App\UseCases\Search\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private $service;

    public function __construct(SearchService $service)
    {
        $this->service = $service;
    }

    // поиск проектов
    public function searchProjects(Request $request): JsonResponse
    {
        $projects = $this->service->projectsResults($request);
        return Response::HTTP_OK(ActiveProjectListResource::collection($projects));
    }

    // поиск пользователей
    public function searchUsers(Request $request): JsonResponse
    {
        $users = $this->service->usersResults($request);
        return Response::HTTP_OK(UserListResource::collection($users));
    }
}
