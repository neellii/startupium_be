<?php

namespace App\Entity\Carrer;

use App\Entity\User\User;
use Database\Factories\CarrerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $country
 * @property string $position
 * @property string $duty
 * @property Date $start_date_at
 * @property Date $last_date_at
 *
*/
class Carrer extends Model
{
    use HasFactory;
    protected $table = 'carrers';
    protected $fillable = ['company', 'position', 'duty', 'user_id', 'last_date_at', 'start_date_at'];
    protected $casts = [
        'start_date_at' => 'datetime',
        'last_date_at' => 'datetime',
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function newFactory()
    {
        return CarrerFactory::new();
    }
}
