<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('material_sizes')->insert([
            ['size_name' => 'XS', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'S', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'M', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'L', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'XL', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'XXL', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => '3XL', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => '4XL', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => '5XL', 'created_at' => now(), 'updated_at' => now()],
            ['size_name' => 'Free Size', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
