<?php
namespace App\Events\User;

use App\Entity\User\Status\Status;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemoveUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $companionId;
    private $userId;

    public function __construct(int $userId, int $companionId)
    {
        $this->companionId = $companionId;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('messages.' . $this->userId * $this->companionId);
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->userId,
                'avatar' => 'https://www.freeiconspng.com/uploads/no-image-icon-0.png',
                'firstname' => 'DELETED',
                'status' => Status::STATUS_DELETED
            ]
        ];
    }
}
