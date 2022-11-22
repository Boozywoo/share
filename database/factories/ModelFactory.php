<?php

$factory->define(App\Models\Client::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'email' => $faker->unique()->safeEmail,
        'phone' => 37544 . rand(1111111, 9999999),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
