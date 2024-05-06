<?php
namespace App\Console\Commands\Sitemap;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use App\Entity\Project\Project;
use App\Entity\User\User;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // add items to the sitemap (url, date, priority, freq)
        $sitemap->add(URL::to('/'), null, '1.0', 'daily');
        $sitemap->add(URL::to('about'));

        $projects = Project::query()->where('status', '=', 'Active')->get();

        // add every project to the sitemap
        foreach ($projects as $project) {
            $sitemap->add(config('app.url') . "/project/$project->id", $project->updated_at);
        }

        $users = User::all();
        // add every user to the sitemap
        foreach ($users as $user) {
            $sitemap->add(config('app.url') . "/profile/$user->id", $user->updated_at);
        }

        // generate your sitemap (format, filename)
        // this will generate file sitemap.xml to your public folder
        $sitemap->store('xml', 'sitemap');
    }
}
