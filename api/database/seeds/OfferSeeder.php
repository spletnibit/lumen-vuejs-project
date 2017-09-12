<?php

use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Offer::class, 10)
          ->create()
          ->each(function($item) {

            $numProducts = rand(10, 50);

            for ($i = 0; $i < $numProducts; $i++) {
              $product = \App\Product::inRandomOrder()->first();
              $item->products()->attach($product->id, [
                'offer_id' => $item->id,
                'qty'         => rand(1, 20),
                'discount'    => time()%2 == 0 ? 0 : (rand(0,30) % 2 ? 0 : rand(0,30)),
                'total'       => 0
              ]);
            }

            $item->save();
        });
    }
}
