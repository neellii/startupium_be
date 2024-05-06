<?php

namespace App\Entity\Feedback;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property User $user
 */
class Feedback extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'text'];
    protected $table = 'feedbacks';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
