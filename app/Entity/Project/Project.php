<?php
namespace App\Entity\Project;

use App\Entity\Blog\Blog;
use App\Entity\Comment\Comment;
use App\Entity\RequireTeam\RequireTeam;
use App\Entity\Residence\City;
use App\Entity\Tag\Tag;
use App\Entity\Traits\Project\ResidenceTrait;
use App\Entity\User\User;
use App\Entity\Wiki\WikiArticle;
use App\Entity\Wiki\WikiSection;
use Carbon\Carbon;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $slug
 * @property int $user_id
 * @property string $status
 * @property string $title
 * @property string $description
 * @property string $reason // причина отклонения проекта
 * @property string $about
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $published_at
 * @property Carbon $expires_at
 * @property Carbon $deleted_at
 * @property User $user
 * @property Comment[] $comments
 * @property Tag[] $tags
 * @property RequireTeam[] $requireTeams
 * @property City $city
 * @property WikiSection $wikiSection
 */
class Project extends Model
{
    use SoftDeletes, HasFactory, ResidenceTrait;

    public const PROJECT_TO_FAVORITES = 'projectToFavorites';
    public const PROJECT_TO_BOOKMARKS = 'projectToBookmarks';
    public const ADD_TO_REPORTS = 'ADD_TO_REPORTS';
    public const PROJECT_FROM_BOOKMARKS = 'projectFromBookmarks';
    public const PROJECT_FROM_FAVORITES = 'projectFromFavorites';
    public const REMOVE_FROM_REPORTS = 'REMOVE_FROM_REPORTS';
    public const PROJECT_APPLY = 'projectApply';

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_MODERATION = 'Moderation';
    public const STATUS_REJECTED = 'Reject';
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CLOSED = 'Closed';
    public const STATUS_DELETED = 'Deleted';

    protected $fillable = ['id', 'title', 'description', 'tags', 'status', 'about', 'user_id',
        'published_at', 'created_at', 'reason', 'deleted_at', 'city_id', 'slug'];

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'Moderation',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_REJECTED => 'Reject',
            self::STATUS_DELETED => 'Deleted'
        ];
    }

    // for admin-panel not use
    public static function new(
        string $title,
        string $about,
        string $description,
        string $tags,
        int $user_id
    ): self {
        return self::create([
            'title' => $title,
            'description' => $description,
            'about' => json_encode($about),
            'tags' => json_encode($tags),
            'status' => self::STATUS_DRAFT,
            'user_id' => $user_id
        ]);
    }

    public function sendToModeration(): void
    {
        // только черновик может отправиться на модерацию
        if (!$this->isDraft()) {
            throw new \DomainException('Project is not draft.');
        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function moderate(): void
    {
        // только проект, отправленный на модерацию, может стать активным
        if ($this->status !== self::STATUS_MODERATION) {
            throw new \DomainException('Project is not send to moderation.');
        }
        $this->update([
            'published_at' => Carbon::now(),
            'status' => self::STATUS_ACTIVE,
            'reason' => null
        ]);
    }

    public function restore()
    {
        if ($this->status !== self::STATUS_DELETED) {
            throw new \DomainException('Project is not deleted.');
        }
        $this->update([
            'deleted_at' => null,
            'status' => self::STATUS_MODERATION
        ]);
    }

    // откатить к черновику с причиной отката
    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reason' => $reason,
        ]);
    }

    public function remove()
    {
        $this->update([
            'status' => self::STATUS_DELETED,
            'deleted_at' => Carbon::now()
        ]);
    }

    public function updateFounder($id) {
        $this->update(['user_id', $id]);
    }

    public function changeStatus($status): void
    {
        // Чтобы не присвоили не существующий статус
        if (!array_key_exists($status, self::statusesList())) {
            throw new \InvalidArgumentException('Undefined status "' . $status . '"');
        }
        // Чтобы не присвоили один и тот же статус
        if ($this->status === $status) {
            throw new \DomainException('Status is already assigned.');
        }
        $this->update(['status' => $status]);
    }

    public function getAuthorWithTrashed(): User
    {
        return findAuthorByProjectIdWithTrashed($this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'project_favorites', 'project_id', 'user_id');
    }

    // SUBSCRIBERS
    public function projectSubscribers()
    {
        return $this
            ->belongsToMany(User::class, 'project_subscribers_refs', 'project_id', 'subscriber_id')
            ->withPivot('require_team_id')
            ->withPivot('subscribed_at')
            ->withPivot('role_id');
    }
    public function hasInSubscribers($userId): bool
    {
        // Существуют ли какие-либо строки для текущего запроса.
        return $this->projectSubscribers()->where('id', $userId)->exists();
    }
    public function addSubscriber($userId, array $attributes = []): void
    {
        if ($this->hasInSubscribers($userId)) {
            throw new \DomainException(config('constants.application_already_exists'));
        }
        $this->projectSubscribers()->attach($userId, $attributes);
    }
    public function removeSubscriber($userId): void
    {
        if (!$this->hasInSubscribers($userId)) {
            throw new \DomainException('Subscriber already removed.');
        }
        $this->projectSubscribers()->detach($userId);
    }
    public function hasInSubscribersProject($userId, $projectId): bool
    {
        return boolval($this->projectSubscribers()
            ->wherePivot('subscriber_id', 'like', $userId ?? "")
            ->wherePivot('project_id', 'like', $projectId ?? "")
            ->wherePivotNotNull('subscribed_at')
            ->first()?->id);
    }


    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null && $this->status === self::STATUS_DELETED;
    }

    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function expire(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public function close(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public function wikiSections()
    {
        return $this->hasMany(WikiSection::class, 'project_id', 'id');
    }

    public function wikiArticles()
    {
        return $this->hasMany(WikiArticle::class, 'project_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'project_id', 'id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'user_bookmarks', 'project_id', 'user_id');
    }

    public function reports()
    {
        return $this->belongsToMany(User::class, 'user_reports', 'project_id', 'user_id');
    }

    public function getCommentsCount()
    {
        return $this->comments()->count();
    }

    public function hasInBookmarks($projectId) {
        $user = findAuthUser();
        return $this::query()->whereHas('bookmarks', function (Builder $query) use ($user) {
            $query->where('user_id', $user?->id);
        })
            ->where('id', $projectId)
            ->get()->contains($projectId);
    }
    public function hasInFavorites($projectId) {
        $user = findAuthUser();
        return $this::query()->whereHas('favorites', function (Builder $query) use ($user) {
            $query->where('user_id', $user?->id);
        })
            ->where('id', $projectId)
            ->get()->contains($projectId);
    }
    public function favoritesCount(string $projectId): int
    {
        $project = findProject($projectId);
        return $project->favorites()->get()->count();
    }
    public function hasInComplaints(string $projectId): bool
    {
        $user = findAuthUser();
        if (!$user) return false;
        return $user->hasInProjectComplaints($projectId);
    }

    // TAG
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_ref', 'project_id', 'tag_id');
    }

    public function hasInTags($tagId): bool
    {
        // Существуют ли какие-либо строки для текущего запроса.
        return $this->tags()->where('id', $tagId)->exists();
    }

    public function addToTags($tagId): void
    {
        if ($this->hasInTags($tagId)) {
            throw new \DomainException('This tag is already added to tags.');
        }
        $this->tags()->attach($tagId);
    }

    public function removeFromTags($tagId): void
    {
        if (!$this->hasInTags($tagId)) {
            throw new \DomainException('This tag is already removed from tags.');
        }
        $this->tags()->detach($tagId);
    }

    public function getTags()
    {
        return Tag::query()->whereHas("tags", function(Builder $query) {
            $query->where("project_id", $this->id);
        })->get();
    }

    // REQUIRE TEAM
    public function requireTeams()
    {
        return $this->belongsToMany(RequireTeam::class, 'require_teams_ref', 'project_id', 'require_team_id')
            ->withPivot('is_hidden');
    }
    public function hasInRequire($tagId): bool
    {
        // Существуют ли какие-либо строки для текущего запроса.
        return $this->requireTeams()->where('id', $tagId)->exists();
    }
    public function addToRequireTeamTags($tagId): void
    {
        if ($this->hasInRequire($tagId)) {
            throw new \DomainException(config('constants.position_already_exists'));
        }
        $this->requireTeams()->attach($tagId);
    }
    public function removeFromRequireTeamTags($tagId): void
    {
        if (!$this->hasInRequire($tagId)) {
            throw new \DomainException(config('constants.no_such_position_exists'));
        }
        $this->requireTeams()->detach($tagId);
    }
    public function getRequireForTeamTags() {
        return RequireTeam::query()->whereHas("requireTeams", function(Builder $query) {
            $query->where("project_id", $this->id);
            $query->where('is_hidden', 'like', 0);
        })
        ->orderByDesc('created_at')
        ->get();
    }

    public function getAllRequireForTeamTags() {
        return RequireTeam::query()->whereHas("requireTeams", function(Builder $query) {
            $query->where("project_id", $this->id);
        })
        ->orderByDesc('created_at')
        ->get();
    }

    public function defaultArticle()
    {
        return $this->belongsToMany(WikiArticle::class, 'default_article_ref', 'project_id', 'article_id')
            ->withPivot('project_id')
            ->withPivot('article_id');
    }
    public function hasInDefaultArticle($articleId)
    {
        return $this->defaultArticle()
            ->wherePivot('project_id', 'like', $this->id)
            ->exists();
    }
    public function addToDefaultArticle($articleId, array $attributes = []): void
    {
        if ($this->hasInDefaultArticle($articleId)) {
            $this->defaultArticle()->detach();
        }
        $this->defaultArticle()->attach($articleId, $attributes);
    }
    public function removeFromDefaultArticle(): void
    {
        $this->defaultArticle()?->detach();
    }

    // BLOGS
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'project_id', 'id');
    }
    public function blogsCount() {
        return $this->blogs()->whereIn('status', [Blog::STATUS_ACTIVE, Blog::STATUS_MODERATION])->count();
    }

    protected static function newFactory()
    {
        return ProjectFactory::new();
    }
}
