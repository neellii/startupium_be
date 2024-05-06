<?php
namespace App\UseCases\User;

use App\Entity\User\Status\Status;
use App\Entity\User\User;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UserService
{
    public function getAvatar(string $dir, string $filename): HttpResponse
    {
        $path = storage_path('app/public/' . $dir . '/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }

    public function getAnyActiveUser(string $id): User
    {
        return findActiveUser($id);
    }

    // get active users order by comments count
    public function getActiveCommentators(): LengthAwarePaginator
    {
        return User::query()
            ->join('user_status', function ($join) {
                $join->on('users.id', '=', 'user_status.user_id')
                    ->where('user_status.status', '=', Status::STATUS_ACTIVE);
            })
            ->leftJoin('project_comments', 'users.id', '=', 'project_comments.user_id')
            ->groupBy('users.id')
            ->orderBy('comments_count', 'desc')
            ->selectRaw('users.*, count(project_comments.id) as comments_count')
            ->paginate(config('constants.users_per_page'));
    }

    public function getActiveUsers(): LengthAwarePaginator
    {
        return User::query()
            ->join('user_status', function ($join) {
                $join->on('users.id', '=', 'user_status.user_id')
                    ->where('user_status.status', '=', Status::STATUS_ACTIVE);
            })
            ->select('users.*')
            ->orderByDesc('created_at')
            ->paginate(config("constants.users_per_page"));
    }
}
