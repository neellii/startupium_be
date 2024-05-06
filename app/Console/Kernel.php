<?php
namespace App\Console;

use App\Console\Commands\Mail\SendEmailCommand;
use App\Console\Commands\Media\ClearUnusedImages;
use App\Console\Commands\Permission\AddPermissionsCommand;
use App\Console\Commands\Project\PopularProjects;
use App\Console\Commands\Project\ProjectActive;
use App\Console\Commands\Project\RejectProject;
use App\Console\Commands\Role\AddRolesCommand;
use App\Console\Commands\Sitemap\GenerateSitemap;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendEmailCommand::class,
        GenerateSitemap::class,
        ClearUnusedImages::class,
        RejectProject::class,
        ProjectActive::class,
        PopularProjects::class,
        AddRolesCommand::class,
        AddPermissionsCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('sitemap:generate')->daily();
        //$schedule->command('projects:popular')->monthlyOn(4, '10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
