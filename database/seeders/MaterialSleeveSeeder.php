<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSleeveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('material_sleeves')->insert([
            ['sleeve_name' => 'Short Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Long Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Sleeveless', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Cap Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Raglan Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Puff Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Bell Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Bishop Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Kimono Sleeve', 'created_at' => now(), 'updated_at' => now()],
            ['sleeve_name' => 'Flutter Sleeve', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
