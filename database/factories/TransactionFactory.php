<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat(2, -2300, 2300),
        'varying' => $faker->boolean(),
        'planned_at' => $faker->dateTimeBetween('now', '+1 year'),
        'actual_at' => $faker->dateTimeBetween('now', '+1 year'),
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'repeating_id' => $faker->randomNumber(5),
        'repeating_interval' => $faker->randomFloat(0, 1, 3).' months'
    ];
});
