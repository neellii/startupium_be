<?php

namespace Database\Seeders;

use App\Entity\Blog\Subject;
use Illuminate\Database\Seeder;

class BlogSubjectTableSeeder extends Seeder
{
    public function run(): void
    {
        Subject::factory()->count(50)->create();
    }
}
