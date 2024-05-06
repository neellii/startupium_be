<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageNotificationResource extends JsonResource
{
    private $message;
    private $userFrom;
    public function __construct(Message $message, User $userFrom)
    {
        $this->message = $message;
        $this->userFrom = $userFrom;
    }
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => Message::POST_MESSAGE,
                'author' => [
                    'id' => $this->userFrom->id,
                    'firstname' => $this->userFrom->firstname,
                    'lastname' => lastnameFormat($this->userFrom->lastname),
                    'avatarUrl' => $this->userFrom->avatar ? $this->userFrom->avatar->url : null,
                    'isOnline' => $this->userFrom->isOnline(),
                    'lastOnlineAt' => $this->userFrom->last_online_at,
                    'status' => $this->userFrom->status?->status,
                ],
                'message' => [
                    'id' => $this->message?->id,
                    'to' => $this->message?->to,
                    'from' => $this->message?->from,
                    'text' => $this->message?->text,
                    'createdAt' => $this->message?->created_at,
                    'type' => 'TEXT'
                ],
                'unreadMessagesCount' => unreadMessagesCountFrom($this->message?->to, $this->message?->from)
            ],
            'readAt' => null,
        ];
    }
}
