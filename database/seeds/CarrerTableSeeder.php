<?php

namespace Database\Seeders;

use App\Entity\Carrer\Carrer;
use Illuminate\Database\Seeder;

class CarrerTableSeeder extends Seeder
{
    public function run()
    {
        Carrer::factory()->count(30)->create();
    }
}
