<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'img_url' => null,
                'fullname' => 'Aditya Giri',
                'username' => 'owner',
                'phone_number' => null,
                'role' => 'owner',
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'img_url' => null,
                'fullname' => 'Ahmad Doni',
                'username' => 'admin',
                'phone_number' => null,
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'img_url' => null,
                'fullname' => 'Hafizh Umar',
                'username' => 'pm',
                'phone_number' => null,
                'role' => 'pm',
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'img_url' => null,
                'fullname' => 'Jabrik',
                'username' => 'karyawan',
                'phone_number' => null,
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
