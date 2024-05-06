<?php
namespace App\Jobs\Image;

use App\Entity\User\Image\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveProjectImages implements ShouldQueue
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
        $images = $user->images()->get();
        foreach ($images as $image) {
            $mediaImage = $image->getMedia(Image::USER_IMAGES)->first();
            if ($mediaImage) {
                $mediaImage->delete();
            }
        }
    }
}
