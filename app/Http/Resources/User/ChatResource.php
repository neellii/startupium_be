<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'status' => $this->status?->status,
            'firstname' => $this->isDeleted() ? 'DELETED' : $this->firstname,
            'lastname' => $this->isDeleted() ? '' : lastnameFormat($this->lastname),
            'isOnline' => $this->when(!$this->isDeleted(), $this->isOnline()),
            'lastOnlineAt' => $this->when(!$this->isDeleted(), $this->last_online_at),
            'unreadMessagesCount' => $this->read,
            'avatarUrl' => $this->isDeleted() ? config('constants.free_icon') : $this->getAvatarUrl(),
            'lastMessage' => $this->lastMessage(Auth::user(), $this)
        ];
    }
}
