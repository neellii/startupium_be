<?php

namespace Database\Factories;

use App\Entity\Carrer\Carrer;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrerFactory extends Factory
{
    protected $model = Carrer::class;
    public function definition()
    {
        $faker = $this->faker;
        return [
            'company' => $faker->company(),
            'position' => $faker->text(50),
            'duty' => $faker->text(100),
            'user_id' => function () use ($faker) {
                $id = $faker->numberBetween(1, 30);
                $user = User::find($id);
                return $user ? $user->id : User::factory()->create()->id;
            }
        ];
    }
}
