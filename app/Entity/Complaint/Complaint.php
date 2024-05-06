<?php

namespace App\Entity\Complaint;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $reason
 * @property string $status
 * @property string $type
 *
*/
class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';
    protected $fillable = ['reason', 'type', 'status'];
    protected $hidden = [];

}
