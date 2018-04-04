<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'description' => $faker->text(150),

        'amount' => sprintf("%0.2f", $faker->randomFloat(2, -2300, 2300)),
        'varying' => $faker->boolean(),

        'planned_on' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        'actual_on' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),

        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },

        'repeating_id' => null,
        'repeating_interval' => $faker->randomFloat(0, 0, 3)
    ];
});