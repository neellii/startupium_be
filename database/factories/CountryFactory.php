<?php

namespace Database\Factories;

use App\Entity\Residence\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;
    public function definition()
    {
        $faker = $this->faker;
        return [
            'title' => $faker->country,
        ];
    }
}
