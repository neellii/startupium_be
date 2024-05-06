<?php

namespace Database\Seeders;

use App\Entity\Wiki\WikiArticle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WikiArticleSeeder extends Seeder
{
    public function run(): void
    {
        WikiArticle::factory()->count(30)->create();
    }
}
