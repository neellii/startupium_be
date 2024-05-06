<?php

use App\Entity\User\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    public function run()
    {
        Skill::factory()->count(200)->create();
    }
}
