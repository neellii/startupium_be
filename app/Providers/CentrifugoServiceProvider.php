<?php

namespace App\Providers;

use App\Entity\Centrifugo\Centrifugo;
use App\BroadCasting\CentrifugoBroadcaster;
use App\Entity\Centrifugo\CentrifugoInterface;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;

class CentrifugoServiceProvider extends ServiceProvider
{
    /**
     * Add centrifugo broadcaster.
     *
     * @param BroadcastManager $broadcastManager
     */
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('centrifugo', function ($app) {
            return new CentrifugoBroadcaster($app->make('centrifugo'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('centrifugo', function ($app) {
            $config = $app->make('config')->get('broadcasting.connections.centrifugo');
            $http = new HttpClient();

            return new Centrifugo($config, $http);
        });

        $this->app->alias('centrifugo', Centrifugo::class);
        $this->app->alias('centrifugo', CentrifugoInterface::class);
    }
}
