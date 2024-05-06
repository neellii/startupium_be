<?php
namespace App\Entity\Notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $casts = [
        'id' => 'string',
        'data' => 'object',
        'read_at' => 'datetime',
        'created_at' => 'datetime'
    ];
    protected $guarded = [];
}
