<?php
namespace App\Entity\User\Avatar;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Avatar extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const USER_AVATARS = 'avatars';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
