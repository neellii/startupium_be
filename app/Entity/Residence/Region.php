<?php

namespace App\Entity\Residence;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'regions';
    protected $fillable = ['title'];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
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
        return RegionFactory::new();
    }
}
