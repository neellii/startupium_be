<?php
namespace Database\Factories;

use App\Entity\User\Technology\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTechnologiesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Technology::class;

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
            'status' => Technology::STATUS_ACTIVE
        ];
    }
}
