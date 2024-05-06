<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your routes model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        // open routes
        Route::prefix('api')
             ->middleware('api')
             ->as('api.')
             ->group(function () {
                 require base_path('routes/api/open/blog.php');
                 require base_path('routes/api/open/combine.php');
                 require base_path('routes/api/open/auth.php');
                 require base_path('routes/api/open/user.php');
                 require base_path('routes/api/open/project.php');
                 require base_path('routes/api/open/comment.php');
                 require base_path('routes/api/open/residence.php');
                 require base_path('routes/api/open/subscribers.php');
                 require base_path('routes/api/open/feedback.php');
             });

        // protected routes
        Route::prefix('api')
         ->middleware(['auth:api', 'token'])
         ->as('api.')
         ->group(function () {
             require base_path('routes/api/protected/blog.php');
             require base_path('routes/api/protected/aws.php');
             require base_path('routes/api/protected/wiki.php');
             require base_path('routes/api/protected/auth.php');
             require base_path('routes/api/protected/chat.php');
             require base_path('routes/api/protected/comment.php');
             require base_path('routes/api/protected/profile.php');
             require base_path('routes/api/protected/project.php');
             require base_path('routes/api/protected/notification.php');
             require base_path('routes/api/protected/centrifugo.php');
             require base_path('routes/api/protected/subscribers.php');
             require base_path('routes/api/protected/communication.php');
         });

         // admin routes
         Route::prefix('api')
            ->middleware(['auth:api', 'token', 'admin'])
            ->as('api.')
            ->group(function () {
                require base_path('routes/api/admin/profile.php');
            });
    }
}
