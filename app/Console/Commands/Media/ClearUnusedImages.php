<?php
namespace App\Console\Commands\Media;

use App\Entity\User\User;
use Illuminate\Console\Command;

class ClearUnusedImages extends Command
{
    protected $signature = 'image:clearUnused';

    protected $description = 'Clear unused images';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $unused = [];
        $users = User::all();
        foreach ($users as $user) {
            $images = $user->images;
            foreach ($images as $image) {
                $url = $image->url;
                $count = 0;
                $projects = $user->projects;
                foreach ($projects as $project) {
                    if (strpos($project->text, $url) !== false) {
                        $count++;
                    }
                }
                if ($count === 0) {
                    $unused[] = $image;
                }
            }
        }
        foreach ($unused as $item) {
            $media = $item->getMedia('user_images')->first();
            if ($media) {
                $media->delete();
            }
            $item->delete();
        }
    }
}
