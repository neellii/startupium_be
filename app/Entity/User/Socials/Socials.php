<?php

namespace App\Entity\User\Socials;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $url
 *
*/
class Socials extends Model
{
    use HasFactory;
    protected $table = 'socials';
    protected $fillable = ['title', 'url', 'user_id'];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
