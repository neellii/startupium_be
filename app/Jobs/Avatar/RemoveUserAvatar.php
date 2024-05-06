<?php
namespace App\Jobs\Avatar;

use App\Entity\User\Avatar\Avatar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// при удалении пользователя адаляю его аватарку из таблицы media и обновляю автарку
class RemoveUserAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $user = findUserWithTrashed($this->userId);
        $mediaAvatars = $user->avatar->getMedia(Avatar::USER_AVATARS)->first();
        if ($mediaAvatars) {
            $mediaAvatars->delete();
        }
    }
}
