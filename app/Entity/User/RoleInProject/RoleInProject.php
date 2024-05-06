<?php

namespace App\Entity\User\RoleInProject;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property bool $mentor
 * @property bool $trainee
 * @property bool $seeker
 * @property bool $investor
 * @property bool $founder
*/
class RoleInProject extends Model
{
    use HasFactory;
    protected $table = 'user_role_in_project';
    protected $fillable = ['mentor', 'trainee', 'seeker', 'investor', 'founder'];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'id'
    ];
    protected $casts = [
        'mentor' => 'bool',
        'trainee' => 'bool',
        'seeker' => 'bool',
        'investor' => 'bool',
        'founder' => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
