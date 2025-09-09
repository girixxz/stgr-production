<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialTextureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('material_textures')->insert([
            ['texture_name' => 'Smooth', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Rough', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Soft', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Coarse', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Glossy', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Matte', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Textured', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Velvet', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Satin', 'created_at' => now(), 'updated_at' => now()],
            ['texture_name' => 'Ribbed', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
