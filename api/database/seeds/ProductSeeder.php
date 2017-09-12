<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\ProductCategory::class, 5)->create();
      
      factory(App\Product::class, 20)
        ->create()
        ->each(function($item) {
            $model = \App\ProductCategory::inRandomOrder()->first();
            $item->category()->associate($model);
            $item->save();
      });
    }
}
