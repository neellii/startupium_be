<?php
namespace App\Jobs\User;

use App\Events\User\RemoveUserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $user = findUserWithTrashed($this->id);
        $contacts = $user->chat()->where('user_id', $user->id)->get();
        foreach ($contacts as $contact) {
            broadcast(new RemoveUserEvent($this->id, $contact->id))->toOthers();
        }
    }
}
