<?php
namespace App\Console\Commands\Notification;

use App\Entity\Notification\Notification;
use App\Http\Resources\Notification\NotificationListResource;
use Illuminate\Console\Command;

class Notifications extends Command
{
    protected $signature = 'notification:group';

    protected $description = 'Grouped notifications by author';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $notifications = $this->groupNotifications(Notification::query()
        ->whereNull('read_at')->get());
    }

    private function groupNotifications($notifications)
    {
        $results = [];
        foreach ($notifications as $notification) {
            if (!empty($notification->data->author)) {
                $key = $notification->data->type . '$' . $notification->data->author->id;
                if (!array_key_exists($key, $results)) {
                    $results[$key] = [
                        'data' => new NotificationListResource($notification)
                    ];
                }
                $results[$key][] = new NotificationListResource($notification);
            }
        }
        return $results;
    }
}
