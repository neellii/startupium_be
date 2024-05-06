<?php
namespace App\Entity\User;

use App\Entity\Blog\Blog;
use App\Entity\Chat\Message;
use App\Entity\Comment\Comment;
use App\Entity\Project\Project;
use App\Entity\Residence\City;
use App\Entity\Settings\NotificationSettings;
use App\Entity\Settings\PrivateSettings;
use App\Entity\Settings\SendByEmailSettings;
use App\Entity\Traits\Project\SubscriberTrait;
use App\Entity\Traits\User\SkillTrait;
use App\Entity\Traits\User\TechnologyTrait;
use App\Entity\User\Avatar\Avatar;
use App\Entity\Carrer\Carrer;
use App\Entity\Feedback\Feedback;
use App\Entity\Token\RefreshToken;
use App\Entity\Traits\User\BlogTrait;
use App\Entity\Traits\User\ComplaintTrait;
use App\Entity\Traits\User\QualityTrait;
use App\Entity\Traits\User\ResidenceTrait;
use App\Entity\Traits\User\RoleTrait;
use App\Entity\User\Image\Image;
use App\Entity\User\Permission\Permission;
use App\Entity\User\Quality\Quality;
use App\Entity\User\Role\Role;
use App\Entity\User\RoleInProject\RoleInProject;
use App\Entity\User\Skill\Skill;
use App\Entity\User\Socials\Socials;
use App\Entity\User\Status\Status;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $psuid
 * @property string $password
 * @property string $desired_position
 * @property Project[] $projects
 * @property Comment[] $comments
 * @property Status status
 * @property User[] users
 * @property Avatar $avatar
 * @property Image[] images
 * @property City $city
 * @property Carrer[] $carrers
 * @property Socials[] $socials
 * @property RoleInProject $rolesInProject
 * @property RefreshToken $refreshToken
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, HasFactory, RoleTrait, ResidenceTrait,
        SkillTrait, TechnologyTrait, SubscriberTrait, QualityTrait, ComplaintTrait, BlogTrait;

    protected $fillable = ['firstname', 'lastname', 'email', 'bio', 'password', 'id', 'password_changed_at',
        'verification_code', 'email_verified_at', 'remember_token', 'last_email_at', 'desired_position', 'city_id', 'psuid'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password_changed_at' => 'datetime',
        'last_online_at' => 'datetime',
        'last_email_at' => 'datetime'
    ];

    protected $cascadeDeletes = ['projects', 'comments'];

    // Filled
    public function filled() {
        $filled = boolval($this->firstname) && boolval($this->desired_position);
        return $filled;
    }

    // User Role
    public function getRoleById($id): Role | null {
        return Role::query()->where('id', 'like', $id ?? "")?->first();
    }
    public function isAdmin() {
      $role = Role::query()->where('title', Role::ADMIN)->first();
      return $this->hasInRoles($role?->id);
    }

    // User permission
    public function getPermissionsByRoleId($id) {
        return Permission::query()->whereHas('roles', function (Builder $query) use ($id) {
            $query->where('role_id', $id);
        })->get()->map(fn ($el) => $el->title);
    }

    // FEEDBACKS
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    // ROLES IN PROJECT
    public function rolesInProject()
    {
        return $this->hasMany(RoleInProject::class, 'user_id', 'id');
    }
    public function getRollesInProject() {
        return $this->rolesInProject()->first();
    }

    // SKILLS
    public function getSkills() {
        return Skill::query()->whereHas('skills', function (Builder $query) {
            $query->where('user_id', $this->id);
        })->get();
    }
    // QUALITIES
    public function getQualities() {
        return Quality::query()->whereHas('qualities', function (Builder $query) {
            $query->where('user_id', $this->id);
        })->get();
    }

    // CARRER
    public function carrers()
    {
        return $this->hasMany(Carrer::class, 'user_id', 'id');
    }

    // SOCIALS
    public function socials()
    {
        return $this->hasMany(Socials::class, 'user_id', 'id');
    }

    public function getResidence()
    {
        return [
            'city' => $this->city,
            'country' => $this->city()->country()
        ];
    }

    // PROJECT
    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    // Refresh Token
    public function refreshToken()
    {
        return $this->hasOne(RefreshToken::class, 'user_id', 'id');
    }
    public function getRefreshToken()
    {
        return $this->refreshToken?->refresh_token;
    }
    public function hasExpiresAtRT()
    {
        return $this->refreshToken?->expires_at->diffInDays(now()) > config('constants.refresh_token_expires_in');
    }

    // AVATAR
    public function getAvatarUrl()
    {
        return getValidMediaUrl($this, $this->avatar?->url);
    }
    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'user_id', 'id');
    }

    // STATUS
    public function status()
    {
        return $this->hasOne(Status::class, 'user_id', 'id');
    }

    public function isDeleted()
    {
        return $this->status ? $this->status->status === Status::STATUS_DELETED && $this->deleted_at : false;
    }

    public static function new($firstname, $lastname, $email): self
    {
        return static::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => bcrypt(Str::random())
        ]);
    }

    public function hasVerifiedEmail()
    {
        return $this->verification_code === null && $this->email_verified_at !== null;
    }

    public function setEmailVerified()
    {
        DB::transaction(function () {
            $this->update([
                'verification_code' => null,
                'email_verified_at' => Carbon::now()
            ]);
            $this->status->update([
                'status' => Status::STATUS_ACTIVE
            ]);
        });
    }

    // IMAGE
    public function images()
    {
        return $this->hasMany(Image::class, 'user_id', 'id');
    }

    // COMMENT
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getCommentsCount()
    {
        return $this->comments()->count();
    }

    // FAVORITES
    public function favorites()
    {
        return $this->belongsToMany(Project::class, 'project_favorites', 'user_id', 'project_id');
    }

    public function hasInFavorites($projectId): bool
    {
        // Существуют ли какие-либо строки для текущего запроса.
        return $this->favorites()->where('id', $projectId)->exists();
    }

    public function addToFavorites($projectId): void
    {
        if ($this->hasInFavorites($projectId)) {
            //$this->removeFromFavorites($projectId); // или такая реализация
            throw new \DomainException('This project is already added to favorites.');
        }
        // если отсутствует, то прикрепи проект
        $this->favorites()->attach($projectId);
    }

    public function removeFromFavorites($projectId): void
    {
        if (!$this->hasInFavorites($projectId)) {
            //$this->addToFavorites($projectId);
            throw new \DomainException('This project is already removed from favorites.');
        }
        // если присутствует, то открепи проект
        $this->favorites()->detach($projectId);
    }

    public function getFavoritesCount()
    {
        return $this->favorites()->count();
    }

    // BOOKMARKS
    public function bookmarks()
    {
        return $this->belongsToMany(Project::class, 'user_bookmarks', 'user_id', 'project_id');
    }

    public function hasInBookmarks($projectId): bool
    {
        // Существуют ли какие-либо строки для текущего запроса.
        return $this->bookmarks()->where('id', $projectId)->exists();
    }

    public function addToBookmarks($projectId): void
    {
        if ($this->hasInBookmarks($projectId)) {
            throw new \DomainException('This project is already added to bookmarks.');
        }
        $this->bookmarks()->attach($projectId);
    }

    public function removeFromBookmarks($projectId): void
    {
        if (!$this->hasInBookmarks($projectId)) {
            throw new \DomainException('This project is already removed from bookmarks.');
        }
        $this->bookmarks()->detach($projectId);
    }

    // SETTINGS
    public function notificationSettings()
    {
        return $this->belongsToMany(NotificationSettings::class, 'notification_settings_ref', 'user_id', 'not_set_id');
    }

    public function sendByEmailSettings()
    {
        return $this->belongsToMany(SendByEmailSettings::class, 'send_by_email_settings_ref', 'user_id', 'send_email_id');
    }

    public function privateSettings()
    {
        return $this->belongsToMany(PrivateSettings::class, 'private_settings_ref', 'user_id', 'pri_set_id');
    }

    // CHAT
    public function chat()
    {
        return $this->belongsToMany(User::class, 'chat_with', 'user_id', 'companion_id');
    }

    public function hasInChat($companion_id): bool
    {
        return $this->chat()->withTrashed()->where('id', $companion_id)->exists();
    }

    public function addToChat($companion_id)
    {
        if ($this->hasInChat($companion_id)) {
            return $companion_id;
        }
        $this->chat()->withTrashed()->attach($companion_id);
        return -1;
    }

    public function removeFromChat($companion_id): void
    {
        if (!$this->hasInChat($companion_id)) {
            throw new \DomainException('This user is already removed from chat.');
        }
        $this->chat()->detach($companion_id);
    }

    public function lastMessage($user1, $user2)
    {
        return Message::query()->where(function ($query) use ($user1, $user2) {
            $query->where('to', $user1->id);
            $query->where('from', $user2->id);
        })->orderByDesc('created_at')->first();
    }

    // IS_ONLINE
    public function getOnlineAttribute(): string
    {
        $activity = DB::table('sessions')->where('user_id', $this->id)->where('last_activity', '>', strtotime('-15 minutes'))->count();
        return $activity ? 'На сайте' : 'Отсутствует';
    }

    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    // NOTIFICATIONS
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'durdom.online.user.' . $this->id;
    }

    // MESSAGE REPORT
    public function messageReport()
    {
        return $this->belongsToMany(Message::class, 'message_report', 'user_id', 'message_id');
    }

    public function hasInMessageReport($message_id): bool
    {
        return $this->messageReport()->where('id', $message_id)->exists();
    }

    public function addToMessageReport($message_id, array $attributes): void
    {
        if ($this->hasInMessageReport($message_id)) {
            return;
        }
        $this->messageReport()->attach($message_id, $attributes);
    }

    public function removeFromMessageReport($message_id): void
    {
        if (!$this->hasInMessageReport($message_id)) {
            return;
        }
        $this->messageReport()->detach($message_id);
    }

    // BLOGS
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id', 'id');
    }
    public function blogsCount() {
        return $this->blogs()->whereIn('status', [Blog::STATUS_ACTIVE, Blog::STATUS_MODERATION])->count();
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
