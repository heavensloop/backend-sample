<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'ip_address' => $faker->ipv4,
        'episode_id' => $faker->randomNumber,
        'message' => $faker->sentence,
    ];
});

$factory->state(App\Comment::class, 'long-comment', function (Faker\Generator $faker) {
    return [
        'message' => $faker->sentence(300)
    ];
});
