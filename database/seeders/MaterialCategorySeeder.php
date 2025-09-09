<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('material_categories')->insert([
            ['material_name' => 'Cotton', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Polyester', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Wool', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Silk', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Linen', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Denim', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Leather', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Synthetic', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Nylon', 'created_at' => now(), 'updated_at' => now()],
            ['material_name' => 'Rayon', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
