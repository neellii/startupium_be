<?php

namespace Database\Seeders;

use App\Entity\Residence\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Region::factory()->count(100)->create();
    }
}
