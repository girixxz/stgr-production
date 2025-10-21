<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sales = [
            [
                'sales_name' => 'Ahmad Hidayat',
                'phone' => '081234567890',
            ],
            [
                'sales_name' => 'Budi Santoso',
                'phone' => '081298765432',
            ],
            [
                'sales_name' => 'Citra Dewi',
                'phone' => '081356789012',
            ],
            [
                'sales_name' => 'Dian Pratama',
                'phone' => '081423456789',
            ],
            [
                'sales_name' => 'Eko Wijaya',
                'phone' => '081534567890',
            ],
            [
                'sales_name' => 'Fitri Rahayu',
                'phone' => '081645678901',
            ],
            [
                'sales_name' => 'Gilang Ramadhan',
                'phone' => '081756789012',
            ],
            [
                'sales_name' => 'Hani Kusuma',
                'phone' => '081867890123',
            ],
        ];

        foreach ($sales as $sale) {
            Sale::create($sale);
        }
    }
}
