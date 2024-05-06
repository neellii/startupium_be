<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\User\Skill\Skill;
use Faker\Generator as Faker;

$factory->define(Skill::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name . ' - разработчик',
        'status' => Skill::STATUS_ACTIVE
    ];
});
