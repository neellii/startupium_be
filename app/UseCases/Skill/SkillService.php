<?php
namespace App\UseCases\Skill;

use App\Entity\User\User;
use Illuminate\Http\Request;
use App\Entity\User\Skill\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SkillService
{
    // для всех пользователей показываем только, прошедшие модерацию навыки,
    // для авторизованного пользователя все
    public function userSkills(string $userId): Collection
    {
        $skillQuery = Skill::query();
        $skillQuery->whereHas('skills', function (Builder $query) use ($userId) {
            $query->where('user_id', $userId);
        });
        if (strval(findAuthUser()?->id) !== $userId) {
            $skillQuery->where('status', Skill::STATUS_ACTIVE);
        }
        return $skillQuery->get();
    }

    // создание или обновление навыков
    public function createOrUpdate(Request $request): Collection
    {
        /** @var User $user*/
        $user = authUser();
        $skills = $request['skills'];
        DB::transaction(function () use ($skills, $user) {
            $this->deleteUserSkill($user);
            foreach ($skills as $title) {
                $skill = Skill::query()->where('title', 'like', $title)->first();
                if (!$skill) {
                    $skill = Skill::query()->make([
                        'title' => $title,
                        'status' => Skill::STATUS_MODERATE
                    ]);
                    $skill->save();
                }
                $user->addToSkills($skill->id);
            }
        });
        return $user->skills()->get();
    }

    private function deleteUserSkill(User $user): void
    {
        $user->skills()->detach();
    }
}
