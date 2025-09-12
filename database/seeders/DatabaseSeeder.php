<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SalesSeeder::class,
            ProductCategorySeeder::class,
            MaterialCategorySeeder::class,
            MaterialTextureSeeder::class,
            MaterialSleeveSeeder::class,
            MaterialSizeSeeder::class,
            ServiceSeeder::class,
            ShippingSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            CustomerSeeder::class,
        ]);
    }
}
