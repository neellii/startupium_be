<?php

namespace App\Entity\User\Quality;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_MODERATE = 'Moderate';

    protected $guarded = [];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function qualities()
    {
        return $this->belongsToMany(User::class, 'qualities_ref', 'quality_id', 'user_id');
    }
}
