<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password123');
        
        // Create one user for each role
        User::create([
            'fullname' => 'Owner User',
            'username' => 'owner',
            'phone_number' => '081234567890',
            'password' => $password,
            'role' => 'owner',
        ]);

        User::create([
            'fullname' => 'Admin User',
            'username' => 'admin',
            'phone_number' => '081234567891',
            'password' => $password,
            'role' => 'admin',
        ]);

        User::create([
            'fullname' => 'Project Manager 1',
            'username' => 'pm1',
            'phone_number' => '081234567892',
            'password' => $password,
            'role' => 'pm',
        ]);

        User::create([
            'fullname' => 'Project Manager 2',
            'username' => 'pm2',
            'phone_number' => '081234567893',
            'password' => $password,
            'role' => 'pm',
        ]);

        User::create([
            'fullname' => 'Karyawan Produksi 1',
            'username' => 'karyawan1',
            'phone_number' => '081234567894',
            'password' => $password,
            'role' => 'karyawan',
        ]);

        User::create([
            'fullname' => 'Karyawan Produksi 2',
            'username' => 'karyawan2',
            'phone_number' => '081234567895',
            'password' => $password,
            'role' => 'karyawan',
        ]);
    }
}
