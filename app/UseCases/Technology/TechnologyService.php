<?php
namespace App\UseCases\Technology;

use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Entity\User\Technology\Technology;
use Illuminate\Database\Eloquent\Collection;

class TechnologyService
{
    // для всех пользователей показываем только, прошедшие модерацию технологии,
    // для авторизованного пользователя все
    public function userTechnologies(string $userId): Collection
    {
        $technologiesQuery = Technology::query();
        $technologiesQuery->whereHas('technologies', function (Builder $query) use ($userId) {
            $query->where('user_id', $userId);
        });
        if (strval(findAuthUser()?->id) !== $userId) {
            $technologiesQuery->where('status', Technology::STATUS_ACTIVE);
        }
        return $technologiesQuery->get();
    }

    // создание или обновление технологий
    public function createOrUpdate(Request $request): Collection
    {
        /** @var User $user*/
        $user = authUser();
        $technologies = $request['technologies'];
        DB::transaction(function () use ($technologies, $user) {
            $this->deleteUserTechnologies($user);
            foreach ($technologies as $title) {
                $technology = Technology::query()->where('title', 'like', $title)->first();
                if (!$technology) {
                    $technology = Technology::query()->make([
                        'title' => $title,
                        'status' => Technology::STATUS_MODERATE
                    ]);
                    $technology->save();
                }
                $user->addToTechnologies($technology->id);
            }
        });
        return $user->technologies()->get();
    }

    private function deleteUserTechnologies(User $user): void
    {
        $user->technologies()->detach();
    }
}
