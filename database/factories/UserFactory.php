<?php
namespace Database\Factories;

use App\Entity\Residence\City;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $active = $this->faker->boolean;
        return [
            'firstname' => $this->faker->name,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'bio' => $this->faker->text(100),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'role' => $active ? $this->faker->randomElement([User::ROLE_USER, User::ROLE_ADMIN]) : User::ROLE_USER,
            'city_id' => function () {
                $id = $this->faker->numberBetween(1, 50);
                $city = City::find($id);
                return $city ? $city->id : City::factory()->create()->id;
            },
        ];
    }
}
