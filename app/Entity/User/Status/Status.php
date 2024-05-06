<?php
namespace App\Entity\User\Status;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string status
 * @property User $user
 */
class Status extends Model
{
    public const STATUS_WAIT = 'WAIT';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_BLOCKED = 'BLOCKED';
    public const STATUS_DELETED = 'DELETED';

    protected $table = 'user_status';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new \DomainException('User is already blocked.');
        }
        $this->status = self::STATUS_BLOCKED;
    }

    public function changeStatus(string $status)
    {
        if ($this->status === $status) {
            throw new \DomainException('Status is already assigned.');
        }
        $this->update(['status' => $status]);
    }
}
