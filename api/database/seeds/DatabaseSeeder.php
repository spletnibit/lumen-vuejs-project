<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call('CustomerSeeder');
//         $this->call('ProductSeeder');
//         $this->call('OfferSeeder');
         $this->call('UserSeeder');
    }
}
