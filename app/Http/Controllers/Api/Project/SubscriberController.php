<?php
namespace App\Http\Controllers\Api\Project;

use App\Entity\User\Permission\Permission;
use Illuminate\Http\Request;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\CreateRequest;
use App\UseCases\Project\SubscriberService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Subscriber\DeleteRequest;
use App\Http\Requests\Subscriber\UpdateMemberRequest;
use App\Http\Requests\Subscriber\UpdateRequest;
use App\Http\Resources\Project\ProjectMembersResource;
use App\Http\Resources\Project\ProjectSubscribersResource;
use App\Http\Resources\Projects\ActiveProjectListResource;

class SubscriberController extends Controller
{
    private $subscriberService;

    public function __construct(SubscriberService $service)
    {
        $this->subscriberService = $service;
    }

    // подписчики на проект
    public function subscribers(Request $request): JsonResource
    {
        $subscribers = $this->subscriberService->subscribers($request);
        return ProjectSubscribersResource::collection($subscribers);
    }

    // участники
    public function members(Request $request): JsonResource {
        $members = $this->subscriberService->members($request);
        return ProjectMembersResource::collection($members);
    }

    // роли участников
    public function membersRoles(Request $request) {
        $roles = $this->subscriberService->getRolesInTeam($request);
        return Response::HTTP_OK(['data' => $roles]);
    }

    // обновить специальность/роль участника
    public function updateMember(UpdateMemberRequest $request) {
        $member = $this->subscriberService->updateMember($request);
        return Response::HTTP_OK(new ProjectSubscribersResource($member));
    }

    // заявки
    public function applications(Request $request): JsonResource {
        $applications = $this->subscriberService->applications($request);
        return ProjectSubscribersResource::collection($applications);
    }

    // количество заявок
    public function applicationCount(Request $request) {
        $count = $this->subscriberService->applicationCount($request);
        return Response::HTTP_OK(['count' => $count]);
    }

    // подать заявку в команду проекта
    public function subscribe(CreateRequest $request) {
        $this->subscriberService->subscribe($request);
        return Response::HTTP_OK(['success' => true]);
    }

    // принять заявку
    public function subscribed(UpdateRequest $request) {
        $subscriber = $this->subscriberService->subscribed($request);
        return Response::HTTP_OK(new ProjectSubscribersResource($subscriber));
    }

    // отлонить заявку - удалить
    public function unsubscribed(DeleteRequest $request) {
        $subscriber = $this->subscriberService->unsubscribed($request);
        return Response::HTTP_OK(new ProjectSubscribersResource($subscriber));
    }

    // проекты на которые подписался пользователь
    public function signedProjects() {
        $projects = $this->subscriberService->getSignedProjects();
        return Response::HTTP_OK(ActiveProjectListResource::collection($projects));
    }

    // проверка доступа к project-management
    public function fetchSubscriber(Request $request) {
        $user = $this->subscriberService->getSubscriber($request);
        return Response::HTTP_OK(new ProjectMembersResource($user));
    }

    // все разрешения для подписчика
    public function fetchPermissions() {
      return Response::HTTP_OK(['data' => Permission::managementPermissionList()]);
    }
}
