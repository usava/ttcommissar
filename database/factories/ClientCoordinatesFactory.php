<?php

use Faker\Generator as Faker;

$factory->define(App\ClientCoordinate::class, function (Faker $faker) {
    return [
        'updated_at' => $faker->dateTimeBetween('-2 days', 'now')
    ];
});
