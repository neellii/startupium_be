<?php
namespace App\Providers;

use App\Events\Comment\CommentEvent;
use App\Events\Message\MessageEvent;
use App\Events\Project\ProjectEvent;
use App\Events\User\ApplyEvent;
use App\Listeners\Comment\CommentEventListener;
use App\Listeners\Message\MessageEventListener;
use App\Listeners\Project\ApplyEventListener;
use App\Listeners\Project\ProjectEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ApplyEvent::class => [
            ApplyEventListener::class
        ],
        CommentEvent::class => [
            CommentEventListener::class
        ],
        ProjectEvent::class => [
            ProjectEventListener::class
        ],
        MessageEvent::class => [
            MessageEventListener::class
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
