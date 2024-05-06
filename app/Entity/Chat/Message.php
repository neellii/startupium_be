<?php
namespace App\Entity\Chat;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int from
 * @property int to
 * @property string text
 * @property bool read
 * @property string type
 */
class Message extends Model
{
    public const TYPE_TEXT = 'TEXT';
    public const TYPE_INVITE = 'INVITE';

    public const POST_MESSAGE = 'postMessage';

    use SoftDeletes;

    protected $guarded = [];

    public function messageReport()
    {
        return $this->belongsToMany(User::class, 'message_report', 'message_id', 'user_id');
    }
}
