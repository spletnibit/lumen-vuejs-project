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

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    $faker->addProvider('sl_SI');
    return [
      'name'    => $faker->name,
      'address' => $faker->streetAddress,
      'city'    => $faker->city,
      'zip'     => $faker->postcode,
      'vat'     => 'SI99999999'
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
      'name'    =>  $faker->sentence(3),
      'unit'    => 'm3',
      'price'   => $faker->randomFloat(),
      'vat'     => 9.5
    ];
});
