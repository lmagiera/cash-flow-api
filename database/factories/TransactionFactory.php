<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

$factory->define(App\Transaction::class, function (Faker $faker) {



    return [
        'description' => $faker->text(20),

        'amount' => sprintf("%0.2f", $faker->randomFloat(2, -2300, 2300)),
        'varying' => $faker->boolean(),

        'planned_on' => Carbon::now()->startOfMonth()->addDays(rand(0,28))->format('Y-m-d'),
        'actual_on' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),

        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },

        'repeating_id' => Uuid::uuid4(),
        'repeating_interval' => 0
    ];
});