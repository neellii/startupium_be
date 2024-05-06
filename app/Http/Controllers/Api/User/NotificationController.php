<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Http\Resources\Notification\NotificationListResource;
use App\UseCases\Notification\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    private $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    // непрочитанные уведомления пользователя
    public function getUnreadNotifications(): JsonResponse
    {
        $notifications = $this->service->unreadNotifications();
        return Response::HTTP_OK($notifications);
    }

    // все уведомления пользователя (кроме уведомлений на сообщения)
    public function getNotifications()
    {
        $notifications = $this->service->allNotifications();
        return Response::HTTP_OK(NotificationListResource::collection($notifications));
    }

    // удалить все уведомления пользователя (кроме уведомлений на сообщения)
    public function removeNotifications()
    {
        $this->service->removeNotifications();
        return Response::HTTP_OK([]);
    }

    // отметить уведомления, как прочитанные
    public function makeNotificationsRead()
    {
        $this->service->makeNotificationsRead();
        return Response::HTTP_OK([]);
    }

    public function makeMessageNotificationsRead()
    {
        $this->service->makeMessageNotificationsRead();
        return Response::HTTP_OK([]);
    }

    // отметить группу уведомлений, как прочитанные
    public function makeNotificationsReadById(UpdateNotificationRequest $request)
    {
        $this->service->makeNotificationsReadById($request);
        return Response::HTTP_OK([]);
    }

    // есть ли не прочитанные уведомления
    public function hasUnreadNotifications() {
        $hasUnread = $this->service->hasUnreadNotifications();
        return Response::HTTP_OK(['hasUnread' => $hasUnread]);
    }
}
