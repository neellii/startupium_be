<?php
namespace Database\Factories;

use App\Entity\Comment\Comment;
use App\Entity\User\User;
use App\Entity\Project\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'comment' => $faker->text(100),
            'user_id' => function () use ($faker) {
                $id = $faker->numberBetween(1, 30);
                $user = User::find($id);
                return $user ? $user->id : User::factory()->create()->id;
            },
            'project_id' => function () use ($faker) {
                $id = $faker->numberBetween(1, 50);
                $project = Project::find($id);
                return $project ? $project->id : Project::factory()->create()->id;
            },
            'child_id' => null
        ];
    }
}
