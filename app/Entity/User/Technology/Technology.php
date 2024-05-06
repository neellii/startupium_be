<?php
namespace App\Entity\User\Technology;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserTechnologiesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// инструменты и технологии, которыми владеет пользователь
/**
 * @property int id
 * @property string title
 * @property string status
 * @property User[] users
 */
class Technology extends Model
{
    use HasFactory;
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_MODERATE = 'Moderate';

    protected $guarded = [];

    public function technologies()
    {
        return $this->belongsToMany(User::class, 'user_technology_ref', 'technology_id', 'user_id');
    }

    protected static function newFactory()
    {
        return UserTechnologiesFactory::new();
    }
}
