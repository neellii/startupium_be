<?php
namespace App\UseCases\Chat;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use App\Http\Requests\Chat\SendMessageReportRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ChatReportService
{
    public function add(SendMessageReportRequest $request, string $contactId, string $messageId): void
    {
        $user = authUser();

        if ($user->id !== (int)$contactId) {
            throw new \DomainException(config('constants.check_user'));
        }

        $attributes = [
            'reason' => $request['reason'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        $this->saveData($user, $messageId, 'addToMessageReport', $attributes);
    }

    public function remove(Request $request, string $contactId, string $messageId): void
    {
        $user = authUser();

        if ($user->id !== (int)$contactId) {
            throw new \DomainException(config('constants.check_user'));
        }

        $this->saveData($user, $messageId, 'removeFromMessageReport');
    }

    public function saveData(User $user, string $messageId, string $method, array $attributes = []): void
    {
        $message = findMessage($messageId);
        $user->$method($message->id, $attributes);
    }

    public function reports(Request $request, string $contactId): Collection
    {
        $user = authUser();
        return Message::query()->whereHas('messageReport', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('from', $contactId)
            ->where('to', $user->id)
            ->get();
    }
}
