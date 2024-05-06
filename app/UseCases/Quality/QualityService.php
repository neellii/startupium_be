<?php
namespace App\UseCases\Quality;

use App\Entity\User\Quality\Quality;
use App\Entity\User\User;
use Illuminate\Http\Request;
use App\Entity\User\Skill\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class QualityService
{
    // создание или обновление навыков
    public function createOrUpdate(Request $request): Collection
    {
        /** @var User $user*/
        $user = authUser();
        $qualities = $request['qualities'];
        DB::transaction(function () use ($qualities, $user) {
            $this->deleteUserQualities($user);
            foreach ($qualities as $title) {
                $quality = Quality::query()->where('title', $title)->first();
                if (!$quality) {
                    $quality = Quality::query()->make([
                        'title' => $title,
                        'status' => Skill::STATUS_MODERATE
                    ]);
                    $quality->save();
                }
                $user->addToQualities($quality->id);
            }
        });
        return $user->qualities()->get();
    }

    private function deleteUserQualities(User $user): void
    {
        $user->qualities()->detach();
    }
}
