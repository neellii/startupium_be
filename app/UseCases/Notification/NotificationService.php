<?php
namespace App\UseCases\Notification;

use App\Entity\Notification\Notification;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Notifications\Message\PostedMessage;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function hasUnreadNotifications(): bool {
       return Notification::query()
            ->where('notifiable_id', authUser()->id)
            ->whereNull('read_at')
            ->where('type', '!=', PostedMessage::class)
            ->orderByDesc('created_at')
            ->first() ? true : false;
    }
    public function unreadNotifications(): array
    {
        $unreads = Notification::query()
                ->where('notifiable_id', authUser()->id)
                ->whereNull('read_at')
                ->orderByDesc('created_at')
                ->select('data', 'id')
                ->cursor();
        return $this->groupNotifications($unreads);
    }

    public function allNotifications(): LengthAwarePaginator
    {
        return Notification::query()
            ->where('notifiable_id', authUser()->id)
            ->where('type', '!=', PostedMessage::class)
            ->orderByDesc('created_at')
            ->paginate(config('constants.notifications_per_page'));
    }

    public function makeNotificationsRead(): void
    {
        $user = authUser();
        if ($user) {
            $user->unreadNotifications()->where('type', '!=', PostedMessage::class)->update(['read_at' => Carbon::now()]);
        }
    }

    public function makeMessageNotificationsRead(): void
    {
        $user = authUser();
        if ($user) {
            $user->unreadNotifications()->where('type', '=', PostedMessage::class)->update(['read_at' => Carbon::now()]);
        }
    }

    public function makeNotificationsReadById(UpdateNotificationRequest $request): void
    {
        $ids = $request['ids'];
        $user = authUser();
        foreach ($ids as $id) {
            Notification::query()
                ->where('id', $id)
                ->whereNull('read_at')
                ->where('notifiable_id', $user->id)
                ->update(['read_at' => Carbon::now()]);
        }
    }

    private function groupNotifications($notifications): array
    {
        $results = [];
        $ids = [];
        foreach ($notifications as $notification) {
            if (!empty($notification->data->author)) {
                $key = $notification->data->type . '-' . $notification->data->author->id;
                $ids[$key][] = $notification->id;
                if (!array_key_exists($key, $results)) {
                    $results[$key] = [
                        'notifications' => [$notification],
                    ];
                }
                $results[$key]['notifications'][0]['total'] = count($ids[$key]);
                $results[$key]['ids'][] = $notification->id;
            }
        }
        return $results;
    }

    public function removeNotifications() {
        Notification::query()
            ->where('notifiable_id', authUser()->id)
            ->where('type', '!=', PostedMessage::class)
            ->delete();
    }

    private function getNotifications($type)
    {
        //$unreads = Notification::query()
            //     ->where('notifiable_id', authUser()->id)
            //     ->whereNull('read_at')
            //     ->selectRaw('count(type) as total, type')
            //     ->groupBy('type');

            // return Notification::query()->joinSub($unreads, 'unreads', function ($join) {
            //     $join
            //         ->on('unreads.type', '=', 'notifications.type');
            // })
            //     ->orderByDesc('created_at')
            //     ->select(['data', 'total'])
            //     ->cursor()
            //     ->groupBy(['data.type']);
    }
}
