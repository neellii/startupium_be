<?php
namespace App\Entity\User\Image;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string url
 * @property User $user
 */
class Image extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const USER_IMAGES = 'user_images';

    protected $guarded = [];
    protected $table = 'user_images';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
