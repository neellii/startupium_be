<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Chat\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    do {
        $from = random_int(1, 20);
        $to = random_int(1, 20);
    } while ($from === $to);

    return [
        'from' => $from,
        'to' => $to,
        'text' => $faker->sentence
    ];
});
