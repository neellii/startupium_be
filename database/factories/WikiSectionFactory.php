<?php

namespace Database\Factories;

use App\Entity\Project\Project;
use App\Entity\Wiki\WikiSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class WikiSectionFactory extends Factory
{
    protected $model = WikiSection::class;
    public function definition(): array
    {
        $faker = $this->faker;
        return [
            'project_id' => function () use ($faker) {
                $id = $faker->numberBetween(1, 50);
                $project = Project::find($id);
                return $project ? $project->id : Project::factory()->create()->id;
            },
            'title' => $faker->name,
        ];
    }
}
