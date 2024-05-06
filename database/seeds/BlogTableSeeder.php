<?php

namespace Database\Seeders;

use App\Entity\Blog\Blog;
use Illuminate\Database\Seeder;

class BlogTableSeeder extends Seeder
{
    public function run(): void
    {
        Blog::factory()->count(50)->create();
    }
}
