<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_categories')->insert([
            ['product_name' => 'T-Shirt', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Hoodie', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Jacket', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Pants', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Shoes', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Hat', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Bag', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Accessories', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Underwear', 'created_at' => now(), 'updated_at' => now()],
            ['product_name' => 'Socks', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
