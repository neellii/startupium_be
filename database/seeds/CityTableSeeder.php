<?php

namespace Database\Seeders;

use App\Entity\Residence\City;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::factory()->count(300)->create();
    }
}
