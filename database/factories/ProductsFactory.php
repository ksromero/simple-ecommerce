<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(15),
        'description' => $faker->text(25),
        'cover_image' => $faker->imageUrl(),
        'price' => $faker->randomFloat(NULL, 2, 8)
    ];
});
