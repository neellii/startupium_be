<?php

namespace Database\Factories;

use App\Entity\Blog\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogSubjectFactory extends Factory
{
    protected $model = Subject::class;
    public function definition(): array
    {
        $faker = $this->faker;
        $status = $faker->randomElement([Subject::STATUS_ACTIVE, Subject::STATUS_CLOSED, Subject::STATUS_DRAFT, Subject::STATUS_MODERATION]);
        return [
            'status' => $status,
            'title' => $faker->text(20),
        ];
    }
}
