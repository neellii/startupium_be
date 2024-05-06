<?php
namespace App\UseCases\Chat;

use Illuminate\Http\Request;
use App\Entity\Chat\Communication;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class CommunicationService {

    public function getMessages(Request $request): LengthAwarePaginator {
        $id = $request['id'] ?? "";
        return Communication::query()->where('project_id', 'like', $id)
            ->orderByDesc('created_at')
            ->paginate(config('constants.messages_per_page'));
    }

    public function create(Request $request): Communication {
        $id = $request['id'] ?? "";
        $value = $request['value'];
        $cdata = $request['cdata'];

        $project = findPivotProjectWithSubscriber($id);
        Gate::authorize('create_message', $project?->pivot?->role_id);
        if (boolval($cdata)) {
            //
        } else {
            $message = Communication::query()->create([
                'user_id' => authUser()->id,
                'project_id' => $project->id,
                'text' => $value
            ]);
            return $message;
        }

    }
}
