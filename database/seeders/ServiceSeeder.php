<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'service_name' => 'Printing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_name' => 'Design',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_name' => 'Packaging',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_name' => 'Consultation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_name' => 'Delivery',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
