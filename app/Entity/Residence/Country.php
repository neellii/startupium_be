<?php

namespace App\Entity\Residence;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'countries';
    protected $fillable = ['title'];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_residence_ref');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_residence_ref');
    }

    protected static function newFactory()
    {
        return CountryFactory::new();
    }
}
