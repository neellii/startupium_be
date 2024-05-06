<?php

namespace App\Console\Commands\Tags;

use App\Entity\RequireTeam\RequireTeam;
use Illuminate\Console\Command;

class StrToUpperFirst extends Command
{
    protected $signature = 'str:to-upper-first';

    protected $description = 'Uppercase first letter of require-team tags';

    public function handle()
    {
        $tags = RequireTeam::query()->get();
        foreach($tags as $tag) {
            $tag->update(['title' => mb_strtoupper_first($tag?->title)]);
        }
        $this->info('Специальности обновлены.');
    }
}
