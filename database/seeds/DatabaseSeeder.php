<?php

use Database\Seeders\BlogSubjectTableSeeder;
use Database\Seeders\BlogTableSeeder;
use Database\Seeders\CarrerTableSeeder;
use Database\Seeders\CityTableSeeder;
use Database\Seeders\CountryTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\WikiArticleSeeder;
use Database\Seeders\WikiSectionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountryTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CarrerTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        //$this->call(MessageTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
        $this->call(TechnologiesTableSeeder::class);
        $this->call(WikiSectionSeeder::class);
        $this->call(WikiArticleSeeder::class);
        $this->call(BlogTableSeeder::class);
        $this->call(BlogSubjectTableSeeder::class);
    }
}
