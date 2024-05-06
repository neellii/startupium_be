<?php
namespace Database\Factories;

use App\Entity\User\Skill\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSkillsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'title' => $faker->unique()->name . ' - разработчик',
            'status' => Skill::STATUS_ACTIVE
        ];
    }
}
