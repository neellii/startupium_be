<?php

use App\Entity\User\Technology\Technology;
use Illuminate\Database\Seeder;

class TechnologiesTableSeeder extends Seeder
{
    public function run()
    {
        Technology::factory()->count(200)->create();
    }
}
