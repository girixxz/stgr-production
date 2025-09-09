<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shippings')->insert([
            ['shipping_name' => 'JNE', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'TIKI', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'POS Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'SiCepat', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'J&T Express', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'Wahana', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'Ninja Express', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'Lion Parcel', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'Anteraja', 'created_at' => now(), 'updated_at' => now()],
            ['shipping_name' => 'Gosend', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
