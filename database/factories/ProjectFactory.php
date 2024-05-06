<?php
namespace Database\Factories;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $status = $faker->randomElement([Project::STATUS_ACTIVE, Project::STATUS_CLOSED, Project::STATUS_DRAFT, Project::STATUS_MODERATION]);
        return [
            'title' => $faker->name,
            'description' => $faker->text(50),
            'status' => $status,
            'published_at' => $status === Project::STATUS_ACTIVE ? Carbon::now()->format('Y-m-d') : null,
            'expires_at' => $status === Project::STATUS_MODERATION ? Carbon::now()->addDays(15)->format('Y-m-d') : null,
            'deleted_at' => $status === Project::STATUS_CLOSED ? Carbon::now()->addDays($faker->numberBetween(1, 6))->format('Y-m-d') : null,
            'user_id' => function () use ($faker) {
                $id = $faker->numberBetween(1, 30);
                $user = User::find($id);
                return $user ? $user->id : User::factory()->create()->id;
            }
        ];
    }
}
