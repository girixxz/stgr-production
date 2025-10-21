<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'customer_name' => 'PT Maju Jaya Tekstil',
                'phone' => '081234567890',
                'province_id' => 1, // Jawa Tengah
                'city_id' => 1, // Semarang
                'district_id' => 1, // Semarang Tengah
                'village_id' => 1, // Purwodinatan
                'address' => 'Jl. Pemuda No. 123',
            ],
            [
                'customer_name' => 'CV Berkah Konveksi',
                'phone' => '081298765432',
                'province_id' => 2, // Jawa Barat
                'city_id' => 5, // Bandung
                'district_id' => 6, // Bandung Wetan
                'village_id' => 7, // Citarum
                'address' => 'Jl. Braga No. 45',
            ],
            [
                'customer_name' => 'UD Sukses Mandiri',
                'phone' => '081356789012',
                'province_id' => 3, // Jawa Timur
                'city_id' => 9, // Surabaya
                'district_id' => 10, // Gubeng
                'village_id' => 13, // Airlangga
                'address' => 'Jl. Dharmawangsa No. 67',
            ],
            [
                'customer_name' => 'Toko Kaos Indonesia',
                'phone' => '081423456789',
                'province_id' => 4, // DKI Jakarta
                'city_id' => 13, // Jakarta Pusat
                'district_id' => 14, // Menteng
                'village_id' => 16, // Menteng
                'address' => 'Jl. Cikini Raya No. 89',
            ],
            [
                'customer_name' => 'Garmen Nusantara',
                'phone' => '081534567890',
                'province_id' => 1, // Jawa Tengah
                'city_id' => 2, // Surakarta
                'district_id' => null,
                'village_id' => null,
                'address' => 'Jl. Slamet Riyadi No. 234',
            ],
            [
                'customer_name' => 'Konveksi Cemerlang',
                'phone' => '081645678901',
                'province_id' => 2, // Jawa Barat
                'city_id' => 7, // Bogor
                'district_id' => null,
                'village_id' => null,
                'address' => 'Jl. Pajajaran No. 56',
            ],
            [
                'customer_name' => 'Fashion House Jakarta',
                'phone' => '081756789012',
                'province_id' => 4, // DKI Jakarta
                'city_id' => 15, // Jakarta Selatan
                'district_id' => null,
                'village_id' => null,
                'address' => 'Jl. Senopati No. 78',
            ],
            [
                'customer_name' => 'Bordir Express',
                'phone' => '081867890123',
                'province_id' => 1, // Jawa Tengah
                'city_id' => 1, // Semarang
                'district_id' => 3, // Semarang Selatan
                'village_id' => null,
                'address' => 'Jl. Pandanaran No. 101',
            ],
            [
                'customer_name' => 'Sablon Kreatif',
                'phone' => '081978901234',
                'province_id' => 3, // Jawa Timur
                'city_id' => 10, // Malang
                'district_id' => null,
                'village_id' => null,
                'address' => 'Jl. Ijen No. 45',
            ],
            [
                'customer_name' => 'Toko Rudi Sport',
                'phone' => '081089012345',
                'province_id' => 2, // Jawa Barat
                'city_id' => 5, // Bandung
                'district_id' => 8, // Coblong
                'village_id' => 10, // Lebak Siliwangi
                'address' => 'Jl. Dago No. 123',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
