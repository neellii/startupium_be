<?php

namespace Database\Seeders;

use App\Entity\Wiki\WikiSection;
use Illuminate\Database\Seeder;

class WikiSectionSeeder extends Seeder
{
    public function run(): void
    {
        WikiSection::factory()->count(30)->create();
    }
}
