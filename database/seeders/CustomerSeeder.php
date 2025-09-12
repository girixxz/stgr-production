<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming province_id=6 (DKI Jakarta), city_id=15 (Jakarta Pusat)
        // In real scenario, you might want to query or use Faker for randomness
        DB::table('customers')->insert([
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'province_id' => 6,
                'city_id' => 15,
                'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'province_id' => 6,
                'city_id' => 16,
                'address' => 'Jl. Thamrin No. 2, Jakarta Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Johnson',
                'phone' => '081234567892',
                'province_id' => 9, // Jawa Barat
                'city_id' => 20, // Bandung (approx)
                'address' => 'Jl. Asia Afrika No. 3, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Brown',
                'phone' => '081234567893',
                'province_id' => 10, // Jawa Tengah
                'city_id' => 25, // Semarang (approx)
                'address' => 'Jl. Pahlawan No. 4, Semarang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Charlie Wilson',
                'phone' => '081234567894',
                'province_id' => 11, // Jawa Timur
                'city_id' => 29, // Surabaya (approx)
                'address' => 'Jl. Tunjungan No. 5, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
