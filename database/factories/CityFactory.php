<?php

namespace Database\Factories;

use App\Entity\Residence\City;
use App\Entity\Residence\Country;
use App\Entity\Residence\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;
    public function definition()
    {
        $faker = $this->faker;
        $countryIds = Country::query()->pluck('id');
        $regionIds = Region::query()->pluck('id');

        $countryId = $faker->randomElement($countryIds);
        $regionId = $faker->randomElement($regionIds);
        return [
            'title' => $faker->city,
            'country_id' => $countryId,
            'region_id' => $regionId,
        ];
    }
}
