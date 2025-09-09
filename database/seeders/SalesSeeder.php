<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales')->insert([
            ['sales_name' => 'John Doe', 'phone_number' => '081234567890', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Jane Smith', 'phone_number' => '081234567891', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Bob Johnson', 'phone_number' => '081234567892', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Alice Brown', 'phone_number' => '081234567893', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Charlie Wilson', 'phone_number' => '081234567894', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Diana Davis', 'phone_number' => '081234567895', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Edward Miller', 'phone_number' => '081234567896', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Fiona Garcia', 'phone_number' => '081234567897', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'George Martinez', 'phone_number' => '081234567898', 'created_at' => now(), 'updated_at' => now()],
            ['sales_name' => 'Helen Rodriguez', 'phone_number' => '081234567899', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
