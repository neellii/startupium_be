<?php
namespace App\Entity\Settings;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property boolean commentAnswer
 * @property boolean newMessage
 * @property boolean likeProject
 * @property boolean popularProjects
 * @property User user
 */
class SendByEmailSettings extends Model
{
    protected $fillable = ['commentAnswer', 'likeProject', 'popularProjects', 'newMessage'];

    protected $casts = [
        'commentAnswer' => 'bool',
        'likeProject' => 'bool',
        'popularProjects' => 'bool',
        'newMessage' => 'bool'
    ];

    public function sendByEmailSettings()
    {
        return $this->belongsToMany(User::class, 'send_by_email_settings_ref', 'send_email_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
