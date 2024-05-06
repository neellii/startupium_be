<?php
namespace App\Http\Controllers\Api\Chat;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\AddUserToContacts;
use App\Http\Requests\Chat\DeleteUserFromContacts;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\User\ChatResource;
use App\UseCases\Centrifugo\CentrifugoService;
use App\UseCases\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private $service;
    private $centrifugoService;
    public function __construct(ChatService $service, CentrifugoService $centrifugoService)
    {
        $this->service = $service;
        $this->centrifugoService = $centrifugoService;
    }

    // список собеседников
    public function getContacts()
    {
        $contacts = $this->service->getContacts();
        return Response::HTTP_OK(ChatResource::collection($contacts));
    }

    // добавить пользователя в чат
    public function add(AddUserToContacts $request): JsonResponse
    {
        $user = $this->service->addToChat($request);
        return Response::HTTP_OK(new ChatResource($user));
    }

    // удалить пользователя из чата
    public function remove(DeleteUserFromContacts $request): JsonResponse
    {
        $user = $this->service->removeFromChat($request);
        return Response::HTTP_OK(new ChatResource($user));
    }

    // получить все сообщения между авторизованным пользователем и его собеседником
    public function getMessagesFor(string $contact_id): JsonResponse
    {
        $messages = $this->service->getMessagesFor($contact_id);
        return Response::HTTP_OK(MessageResource::collection($messages));
    }

    // отправить сообщение собеседнику
    public function createMessage(SendMessageRequest $request, string $contact_id): JsonResponse
    {
        $message = $this->service->sendMessage($request, $contact_id);
        $this->centrifugoService->notifyPostMessage($message);
        return Response::HTTP_CREATED(new MessageResource($message));
    }

    // удалить сообщение
    public function removeMessage(Request $request, string $contact_id, string $message_id): JsonResponse
    {
        $this->service->removeMessage($request, $contact_id, $message_id);
        return Response::HTTP_OK([]);
    }

    // обновить сообщение
    public function updateMessage(SendMessageRequest $request, string $contact_id, string $message_id): JsonResponse
    {
        $message = $this->service->updateMessage($request, $contact_id, $message_id);
        return Response::HTTP_OK(new MessageResource($message));
    }

    public function hasUnreadMessages(): JsonResponse {
        $count = $this->service->hasUnreadMessages();
        return Response::HTTP_CREATED(['exists' => $count]);
    }
}
