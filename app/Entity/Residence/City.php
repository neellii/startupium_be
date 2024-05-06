<?php

namespace App\Entity\Residence;

use App\Entity\Project\Project;
use App\Entity\Residence\Country;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CityFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Country $country
*/
class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cities';
    protected $fillable = ['title', 'region_id', 'country_id'];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
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
        return CityFactory::new();
    }
}
