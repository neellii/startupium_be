<?php
namespace App\Entity\Settings;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property boolean pageIndexing
 * @property User user
*/
class PrivateSettings extends Model
{
    public function privateSettings()
    {
        return $this->belongsToMany(User::class, 'private_settings_ref', 'pri_set_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
        'pageIndexing' => 'bool'
    ];

    protected $fillable = ['pageIndexing'];
}
