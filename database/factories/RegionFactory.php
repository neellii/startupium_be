<?php

namespace Database\Factories;

use App\Entity\Residence\Country;
use App\Entity\Residence\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;
    public function definition()
    {
        $faker = $this->faker;
        $ids = Country::query()->pluck('id');
        $id = $faker->randomElement($ids);
        return [
            'title' => $faker->unique()->word(),
            'country_id' => $id,
        ];
    }
}
