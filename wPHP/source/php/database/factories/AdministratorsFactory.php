<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

$factory->define(\App\Models\Administrator::class, function (Faker $faker) {
    return [
        'account' => 'admin',
        'nickname' => $faker->userName,
        'avatar' => $faker->imageUrl(),
        'password' => Hash::make(md5('123456')),
        'role_id' => 0,
    ];
});
