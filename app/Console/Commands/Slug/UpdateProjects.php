<?php

namespace App\Console\Commands\Slug;

use App\Entity\Project\Project;
use App\UseCases\Slug\SlugService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateProjects extends Command
{
    protected $signature = 'slug:projects';

    protected $description = 'Update column slug in projects using project title';

    private $service;
    public function __construct(SlugService $slugService)
    {
        parent::__construct();
        $this->service = $slugService;
    }

    public function handle()
    {
        Project::query()->chunk(200, function (Collection $projects) {
            foreach ($projects as $project) {
                if (!$project?->slug) {
                    if ($project->status === Project::STATUS_DRAFT) {
                        $slug = generateDraftSlug();
                        $project->update(['slug' => $slug]);
                        $this->info('Проект - ' . $project->title . ' обновлен.');
                    }
                    else {
                        $slug = $this->service->generate($project->title);
                        $project->update(['slug' => $slug]);
                        $this->info('Проект - ' . $project->title . ' обновлен.');
                    }
                }
            }
        });
    }
}
