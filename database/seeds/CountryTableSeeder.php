<?php

namespace Database\Seeders;

use App\Entity\Residence\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    public function run()
    {
        Country::factory()->count(20)->create();
    }
}
