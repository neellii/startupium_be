<?php
namespace App\Entity\Settings;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property boolean showCommentsAnswer
 * @property boolean showComments
 * @property boolean showLikes
 * @property boolean showPublicProjects
 * @property boolean showRejectProjects
 * @property boolean showMessages
 * @property boolean showBookmarks
 * @property boolean showReports
 * @property User $user
*/
class NotificationSettings extends Model
{
    protected $guarded = [];

    public function notificationSettings()
    {
        return $this->belongsToMany(User::class, 'notification_settings_ref', 'not_set_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
        'showCommentsAnswer' => 'bool',
        'showComments' => 'bool',
        'showLikes' => 'bool',
        'showPublicProjects' => 'bool',
        'showRejectProjects' => 'bool',
        'showMessages' => 'bool',
        'showBookmarks' => 'bool',
        'showReports' => 'bool',
    ];
}
