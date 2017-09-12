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
      'email' => $faker->email,
      'password' => app('hash')->make('secret')
    ];
});

$factory->define(App\Offer::class, function (Faker\Generator $faker) {
    $customer = \App\Customer::inRandomOrder()->first();
    return [
      'customer_id'       => $customer->id,
      'subtotal'          => 0,
      'subtotal_discount' => 0,
      'subtotal_vat'      => 0,
      'total'             => 0,
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \Faker\Provider\at_AT\Payment($faker));

    return [
      'name'    => ucfirst($faker->name),
      'address' => $faker->streetAddress,
      'city'    => $faker->city,
      'zip'     => $faker->postcode,
      'vat'     => $faker->vat(false)
    ];
});

$factory->define(App\ProductCategory::class, function (Faker\Generator $faker) {
    return [
      'name' => ucfirst(implode(' ', $faker->words()))
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    $units = ['kom', 'm', 'm2', 'm3', 'kg', 'h'];
    return [
      'name'    =>  ucfirst(implode(' ', $faker->words())),
      'unit'    => $units[$faker->numberBetween(0, 5)],
      'price'   => $faker->randomFloat(2, 5, 5000),
      'vat'     => $faker->randomDigit % 2 ? 9.5 : 22
    ];
});
