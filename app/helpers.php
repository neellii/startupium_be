<?php

use App\Entity\Blog\Blog;
use App\Entity\Chat\Message;
use App\Entity\Comment\BlogComment;
use App\Entity\Comment\Comment;
use App\Entity\Crypto\CryptoJsAes;
use App\Entity\Project\Project;
use App\Entity\RequireTeam\RequireTeam;
use App\Entity\Residence\City;
use App\Entity\Residence\Country;
use App\Entity\Residence\Region;
use App\Entity\Token\RefreshToken;
use App\Entity\User\Avatar\Avatar;
use App\Entity\User\Role\Role;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

if (!function_exists('my_paginate')) {
    function my_paginate($comments, int $perPage): LengthAwarePaginator
    {
        $array = Collection::unwrap($comments);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($array, $perPage * ($currentPage - 1), $perPage);
        return new LengthAwarePaginator(
            $currentItems,
            count($comments),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}

if (!function_exists('saveData')) {
    function saveData(User $user, string $projectId, string $method, array $attributes = [])
    {
        $project = findActiveProject($projectId);
        $user->$method($project->id, $attributes);
        return $project;
    }
}

if (!function_exists('arrayToString')) {
    function arrayToString($array)
    {
        return '[' . implode(',', array_map(function ($tag) {
            return $tag->tag;
        }, Collection::unwrap($array))) . ']';
    }
}

if (!function_exists('findUser')) {
    function findUser(string $id): User
    {
        try {
            /** @var User $user */
            $user = User::query()->where('id', 'like', $id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.user_not_found'));
        }
        return $user;
    }
}

if (!function_exists('findActiveUser')) {
    function findActiveUser(string $id): User
    {
        $user = User::query()
        ->join('user_status', function ($join) use ($id) {
            $join->on('users.id', 'like', 'user_status.user_id')
                ->where('user_status.user_id', 'like', $id)
                ->where('user_status.status', 'like', Status::STATUS_ACTIVE);
        })->select('users.*')
        ->first();
        if (!$user) {
            throw new \DomainException(config('constants.user_not_found'));
        }
        return $user;
    }
}

if (!function_exists('authUser')) {
    function authUser(): User
    {
        /** @var User $user */
        $user = findAuthUser();
        if (!$user) {
            throw new \DomainException(config('constants.user_not_found'));
        }
        return $user;
    }
}

// find auth user with laravel-passport
if (!function_exists('findAuthUser')) {
    function findAuthUser(): User | null
    {
        /** @var User $user */
        return Auth::guard('api')->user();
    }
}

if (!function_exists('findUserWithTrashed')) {
    function findUserWithTrashed($id): User
    {
        try {
            /** @var User $user */
            $user = User::query()->withTrashed()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.user_not_found'));
        }
        return $user;
    }
}

if (!function_exists('findProject')) {
    function findProject(string $id): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findProjectBySlug')) {
    function findProjectBySlug(string $slug): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()->where('slug', 'like', $slug)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findProjectWithTrashed')) {
    function findProjectWithTrashed($id): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()->withTrashed()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findAuthorByProjectIdWithTrashed')) {
    function findAuthorByProjectIdWithTrashed($id): User
    {
        try {
            /** @var User $user */
            $user = User::query()->withTrashed()->wherehas('projects', function (Builder $query) use ($id) {
                $query->withTrashed()->where('id', $id);
            })->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.user_not_found'));
        }
        return $user;
    }
}

if (!function_exists('findAuthProject')) {
    function findAuthProject($id): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()
                ->where('id','like', $id)
                ->where('user_id', authUser()->id)
                ->firstOrFail();
            ;
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findAuthProjectBySlug')) {
    function findAuthProjectBySlug($slug): Project
    {
        try {
            /** @var Project $project */
            $project = Project::query()
                ->where('slug','like', $slug)
                ->where('user_id', authUser()->id)
                ->firstOrFail();
            ;
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findMessage')) {
    function findMessage($id): Message
    {
        try {
            /** @var Message $message */
            $message = Message::query()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.message_not_found'));
        }
        return $message;
    }
}

if (!function_exists('findAuthMessage')) {
    function findAuthMessage($id): Message
    {
        try {
            /** @var Message $message */
            $message = Message::query()
                ->where('id', $id)
                ->where('from', auth()->id())
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.message_not_found'));
        }
        return $message;
    }
}

if (!function_exists('findAuthComment')) {
    function findAuthComment($id): Comment
    {
        try {
            /** @var Comment $comment */
            $comment = Comment::query()
                ->where('id', $id)
                ->where('user_id', authUser()->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.comment_not_found'));
        }
        return $comment;
    }
}

if (!function_exists('findAuthBlogComment')) {
    function findAuthBlogComment($id): BlogComment
    {
        try {
            /** @var BlogComment $comment */
            $comment = BlogComment::query()
                ->where('id', $id)
                ->where('user_id', authUser()->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.comment_not_found'));
        }
        return $comment;
    }
}

if (!function_exists('findComment')) {
    function findComment($id): Comment
    {
        try {
            /** @var Comment $comment */
            $comment = Comment::query()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.comment_not_found'));
        }
        return $comment;
    }
}

if (!function_exists('findBlogComment')) {
    function findBlogComment($id): BlogComment
    {
        try {
            /** @var BlogComment $comment */
            $comment = BlogComment::query()->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException(config('constants.comment_not_found'));
        }
        return $comment;
    }
}

if (!function_exists('findUserByEmail')) {
    function findUserByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }
}

if (!function_exists('findUserByEmailAndPSUID')) {
    function findUserByEmailAndPSUID($email, $psuid)
    {
        return User::query()
            ->where('email', 'like', $email)
            ->where('psuid', 'like', $psuid)
            ->first();
    }
}

if (!function_exists('sendEmail')) {
    function sendEmail($user)
    {
        $data = [
            'name' => $user->firstname,
            'verify_url' => config('app.origin') . '/verification/' . $user->verification_code
        ];
        Mail::to($user->email)->send(new \App\Mail\Mail($data));
    }
}

if (!function_exists('saveOrCreateCountry')) {
    function saveOrCreateCountry($title)
    {
        return Country::query()->where('title', 'like', $title)->firstOrCreate([
            'title' => $title
        ]);
    }
}

if (!function_exists('getOrCreateCity')) {
    function getOrCreateCity($title, $country): City
    {
        /** @var City $city */
        $city = City::query()->where('title', 'like', $title)->where('country_id', $country->id)->first();
        if (!$city) {
            $city = City::query()->make(
                ['title' => $title]
            );
            $city->country()->associate($country);
            $city->saveOrFail();
        }
        return $city;
    }
}

if (!function_exists('findRequirePosition')) {
    function findRequirePosition($title): RequireTeam
    {
        /** @var RequireTeam $position */
        try {
            $position = RequireTeam::query()->where("title", 'like', $title)->firstOrFail();
        }
        catch (ModelNotFoundException $ex) {
            throw new \DomainException('Такая специальность отсутсвует.');
        }

        return $position;
    }
}

if (!function_exists('findRequirePositionWithOwnProject')) {
    function findRequirePositionWithOwnProject($title, string $projectId): RequireTeam
    {
        /** @var RequireTeam $position */
        try {
            $position = RequireTeam::query()
                ->where('title', 'like', $title)
                ->join('require_teams_ref', function (JoinClause $join) use ($projectId) {
                    $join->on('require_teams_ref.require_team_id', 'like', 'require_teams.id');
                    $join->where('require_teams_ref.project_id', 'like', $projectId);
                })
                ->select('require_teams.*')
                ->firstOrFail();
        }
        catch (ModelNotFoundException $ex) {
            throw new \DomainException('Такая специальность отсутсвует.');
        }
        return $position;
    }
}

if (!function_exists('findRequirePositionById')) {
    function findRequirePositionById($id): RequireTeam
    {
        /** @var RequireTeam $position */
        try {
            $position = RequireTeam::query()->where("id", 'like', $id)->firstOrFail();
        }
        catch (ModelNotFoundException $ex) {
            throw new \DomainException('Такая специальность отсутсвует.');
        }

        return $position;
    }
}

if (!function_exists('findOrCreateRole')) {
    function findOrCreateRole($role): Role
    {
        $role = Role::query()->where("title", 'like', $role['title'])->firstOrCreate($role);
        if (!$role) {
            throw new \DomainException('Такая роль отсутсвует.');
        }
        return $role;
    }
}

if (!function_exists('findProjectWithSubscriber')) {
    function findProjectWithSubscriber($projectId): Project
    {
        $project = Project::query()
            ->where('id', 'like', $projectId)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->whereHas('projectSubscribers', function (Builder $query) use ($projectId) {
                $query->where('project_id', 'like', $projectId);
                $query->where('subscriber_id', 'like', authUser()->id);
                $query->whereNotNull('subscribed_at');
            })
            ->first();
        if (!$project) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findPivotProjectWithSubscriber')) {
    function findPivotProjectWithSubscriber($projectId): Project
    {
        $user = authUser();
        $project = $user->projectSubscribers()
            ->wherePivot('project_id', 'like', $projectId)
            ->wherePivot('subscriber_id', 'like', $user->id)
            ->wherePivotNotNull('subscribed_at')
            ->first();
        if (!$project) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('getSubscribersWithoutAuthor')) {
    function getSubscribersWithoutAuthor($project)
    {
        return $project->projectSubscribers()
            ->wherePivot('project_id', 'like', $project->id)
            ->wherePivot('subscriber_id', 'not like', $project?->user?->id)
            ->wherePivotNotNull('subscribed_at')
            ->orderByPivot('subscribed_at', 'desc')
            ->get();
    }
}

if (!function_exists('handleEncrypted')) {
    function handleEncrypted($data)
    {
        $key = config('crypto.cipherSecretKey');
        return base64_encode(CryptoJsAes::encrypt($data, $key));
    }
}

if (!function_exists('handleDecrypt')) {
    function handleDecrypt($data)
    {
        $key = config('crypto.cipherSecretKey');
        return CryptoJsAes::decrypt($data, $key);
    }
}

if (!function_exists('createAccessToken')) {
    function createAccessToken(User $user)
    {
        $tokenResult = $user->createToken('accessToken', []);
        $expiresAt = now()->addMinutes(config('constants.access_token_expires_in'));
        $tokenResult?->token?->update([
            'expires_at' => $expiresAt
        ]);
        return ['token' => $tokenResult?->accessToken, 'expiresAt' => $expiresAt->diffInSeconds()];
    }
}

// создаем токен
if (!function_exists('createRefreshToken')) {
    function createRefreshToken(User $user)
    {
        $tokenResult = $user->createToken('refreshToken', []);
        $expiresAt = now()->addDays(config('constants.refresh_token_expires_in'));

        $tokenResult?->token?->update(
            ['expires_at' => $expiresAt]
        );
        return $tokenResult?->token?->id;
    }
}

if (!function_exists('findActiveProject')) {
    function findActiveProject($id): Project
    {
        $project = Project::query()
            ->where('id', 'like', $id)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->first();
        if (!$project) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findAuthActiveProject')) {
    function findAuthActiveProject($id): Project
    {
        $project = Project::query()
            ->where('id', 'like', $id)
            ->where('user_id', authUser()->id)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->first();
        if (!$project) {
            throw new \DomainException(config('constants.project_not_found'));
        }
        return $project;
    }
}

if (!function_exists('findCountryByTitleAndId')) {
    function findCountryByTitleAndId($title, $id): Country | null
    {
        return Country::query()
            ->where('id', 'like', $id ?? "")
            ->where('title', 'like', $title ?? "")
            ->first();
    }
}

if (!function_exists('findRegionById')) {
    function findRegionById($regionId, $countryId): Region | null
    {
        return Region::query()
            ->where('id', 'like', $regionId ?? "")
            ->where('country_id', 'like', $countryId ?? "")
            ->first();
    }
}

if (!function_exists('findCityById')) {
    function findCityById($cityId, $countryId, $regionId): City | null
    {
        if (!$regionId) {
            return City::query()
                ->whereNull('region_id')
                ->where('id', 'like', $cityId ?? "")
                ->where('country_id', 'like', $countryId ?? "")
                ->first();
        }
        return City::query()
            ->where('id', 'like', $cityId ?? "")
            ->where('region_id', 'like', $regionId ?? "")
            ->where('country_id', 'like', $countryId ?? "")
            ->first();
    }
}

if (!function_exists('findCityByTitle')) {
    function findCityByTitle($title, $countryId, $regionId): City | null
    {
        if (!$regionId) {
            return City::query()
                ->whereNull('region_id')
                ->where('title', 'like', $title ?? "")
                ->where('country_id', 'like', $countryId ?? "")
                ->first();
        }
        return City::query()
            ->where('title', 'like', $title ?? "")
            ->where('region_id', 'like', $regionId ?? "")
            ->where('country_id', 'like', $countryId ?? "")
            ->first();
    }
}

// используется в ProfileService and User
if (!function_exists('getValidMediaUrl')) {
    function getValidMediaUrl(User $user, string | null $requestUrl): string | null
    {
        $avatar = Avatar::query()->where('user_id', $user->id)->first();
        $url = $avatar?->url;

        if ($url !== $requestUrl) {
            return null;
        }

        $collectionName = Avatar::USER_AVATARS . '' . $user?->id . '';
        $media = $avatar?->getMedia($collectionName)?->last();
        if (!$media) return null;

        $name = $media?->name;
        $mediaUrl = $media?->getUrl();

        if (str_ends_with($url, $name)) {
            if ($mediaUrl !== $url) {
                $avatar->update([
                    'url' => $mediaUrl
                ]);
            }
            return $mediaUrl;
        }
        return null;
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($phrase, $limit = 450): string
    {
        $slug = str()->slug($phrase);
        if( strlen($slug) > $limit ) return str()->substr($slug, 0, $limit);
        else return $slug;
    }
}

if (!function_exists('generateDraftSlug')) {
    function generateDraftSlug(): string
    {
        return uniqid('draft-', true) . '.' . uniqid('', true);
    }
}

if (!function_exists('unreadMessagesCountFrom')) {
    function unreadMessagesCountFrom($toId, $fromId): string
    {
        return Message::query()
            ->where('to', $toId)
            ->where('from', $fromId)
            ->where('read', '=', false)
            ->groupBy('from')
            ->count();
    }
}

if (!function_exists('findProjectBlogBySlug')) {
    function findProjectBlogBySlug($slug, $project_id): Blog
    {
        $blog = Blog::query()->where('slug', 'like', $slug)->where('project_id', 'like', $project_id)->first();
        if (!$blog) {
            throw new \DomainException(config('constants.content_not_found'));
        }
        return $blog;
    }
}

if (!function_exists('findProjectBlogBySlugAsNull')) {
    function findProjectBlogBySlugAsNull($slug, $project_id): Blog | null
    {
        return Blog::query()->where('slug', 'like', $slug)->where('project_id', 'like', $project_id)->first();

    }
}

if (!function_exists('findUserBlogBySlug')) {
    function findUserBlogBySlug($slug, $user_id): Blog
    {
        $blog = Blog::query()->where('slug', 'like', $slug)->where('user_id', 'like', $user_id)->first();
        if (!$blog) {
            throw new \DomainException(config('constants.content_not_found'));
        }
        return $blog;
    }
}

if (!function_exists('findUserBlogBySlugAsNull')) {
    function findUserBlogBySlugAsNull($slug, $user_id): Blog | null
    {
        return Blog::query()->where('slug', 'like', $slug)->where('user_id', 'like', $user_id)->first();
    }
}

if (!function_exists('findBlogBySlug')) {
    function findBlogBySlug($slug): Blog
    {
        $blog = Blog::query()->where('slug', 'like', $slug)->first();
        if (!$blog) {
            throw new \DomainException(config('constants.content_not_found'));
        }
        return $blog;
    }
}

if (!function_exists('lastnameFormat')) {
    function lastnameFormat($lastname): string
    {
        if (!$lastname || strtolower($lastname) === "null" || strtolower($lastname) === "undefined") {
            return "";
        }
        return $lastname;
    }
}

if (!function_exists('numberOut')) {
    function numberOut(string $input)
      {
         $hash=md5($input);
         $calculate=hexdec(substr($hash,0,5)); //take out 3 digits
         $maxhex=65535; //3 digit hex ,65535 for 4 digit hex and so on...
         $out = ($calculate*1000)/$maxhex;
         return (int) round($out);
      }
}

if (!function_exists('mb_strtoupper_first')) {
    function mb_strtoupper_first($str, $encoding = 'UTF8')
{
    return
        mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
        mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
}
}
