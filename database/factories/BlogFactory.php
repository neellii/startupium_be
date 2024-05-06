<?php

namespace Database\Factories;

use App\Entity\Blog\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    protected $model = Blog::class;
    public function definition(): array
    {
        $faker = $this->faker;
        $status = $faker->randomElement([Blog::STATUS_ACTIVE, Blog::STATUS_CLOSED, Blog::STATUS_DRAFT, Blog::STATUS_MODERATION]);
        return [
            'status' => $status,
            'title' => $faker->text(20),
            'description' => $faker->text(100)
        ];
    }
}
