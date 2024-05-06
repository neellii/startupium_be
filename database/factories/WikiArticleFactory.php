<?php

namespace Database\Factories;

use App\Entity\Project\Project;
use App\Entity\Wiki\WikiArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

class WikiArticleFactory extends Factory
{
    protected $model = WikiArticle::class;
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
            'text' => $faker->text(200)
        ];
    }
}
