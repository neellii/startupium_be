<?php
namespace App\UseCases\Combine;

use App\Entity\Project\Project;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class CombineService
{
    public function fetchUsersProjects(): LengthAwarePaginator {
        $users = User::query()
            ->join('user_status', function ($join) {
                $join->on('users.id', '=', 'user_status.user_id')
                    ->where('user_status.status', '=', Status::STATUS_ACTIVE);
            })
            ->select('users.*')
            ->paginate(config("constants.users_per_page"));
        $projects = Project::query()
            ->where('status', 'like', Project::STATUS_ACTIVE)
            ->orWhere('status', 'like', Project::STATUS_MODERATION)
            ->orderByDesc('created_at')
            ->paginate(config("constants.projects_per_page"));

        return $this->merge($users, $projects);
    }

    private function merge(LengthAwarePaginator $collection1, LengthAwarePaginator $collection2)
    {
        $path = "";
        $perPage = 0;
        $total = $collection1->total() - $collection2->total();
        $items = array_merge($collection1->items(), $collection2->items());

        if ($total >= 0) {
            $total = $collection1->total();
            $path = $collection1->path();
            $perPage = $collection1->perPage();
        } else {
            $total = $collection2->total();
            $path = $collection2->path();
            $perPage = $collection2->perPage();
        }

        uasort($items, function ($a, $b) {
            return new DateTime($b->created_at) <=> new DateTime($a->created_at);
        });

        $paginator = new LengthAwarePaginator($items, $total, $perPage, null,
            ['path' => $path]
        );
        return $paginator;
    }


}
