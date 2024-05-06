<?php

namespace App\Entity\Chat;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'communication';
    protected $casts = [
        'read' => 'bool'
    ];
    protected $fillable = ['text', 'user_id', 'project_id'];

    public function getAuthor($id) {
        return User::query()->where('id', 'like', $id)->first();
    }
}
