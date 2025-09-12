<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinceCities = [
            'Aceh' => ['Banda Aceh', 'Lhokseumawe', 'Sabang'],
            'Bali' => ['Denpasar', 'Badung', 'Gianyar'],
            'Banten' => ['Serang', 'Tangerang', 'Cilegon'],
            'Bengkulu' => ['Bengkulu', 'Curup'],
            'DI Yogyakarta' => ['Yogyakarta', 'Sleman', 'Bantul'],
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur'],
            'Gorontalo' => ['Gorontalo', 'Limboto'],
            'Jambi' => ['Jambi', 'Sungai Penuh'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cirebon'],
            'Jawa Tengah' => ['Semarang', 'Surakarta', 'Magelang', 'Pekalongan'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Kediri', 'Madiun'],
            'Kalimantan Barat' => ['Pontianak', 'Singkawang'],
            'Kalimantan Selatan' => ['Banjarmasin', 'Banjarbaru'],
            'Kalimantan Tengah' => ['Palangka Raya', 'Sampit'],
            'Kalimantan Timur' => ['Samarinda', 'Balikpapan', 'Bontang'],
            'Kalimantan Utara' => ['Tanjung Selor', 'Tarakan'],
            'Kepulauan Bangka Belitung' => ['Pangkal Pinang', 'Tanjung Pandan'],
            'Kepulauan Riau' => ['Batam', 'Tanjung Pinang'],
            'Lampung' => ['Bandar Lampung', 'Metro'],
            'Maluku' => ['Ambon', 'Tual'],
            'Maluku Utara' => ['Ternate', 'Tidore'],
            'Nusa Tenggara Barat' => ['Mataram', 'Bima'],
            'Nusa Tenggara Timur' => ['Kupang', 'Maumere'],
            'Papua' => ['Jayapura', 'Merauke'],
            'Papua Barat' => ['Manokwari', 'Sorong'],
            'Riau' => ['Pekanbaru', 'Dumai'],
            'Sulawesi Barat' => ['Mamuju', 'Majene'],
            'Sulawesi Selatan' => ['Makassar', 'Parepare', 'Palopo'],
            'Sulawesi Tengah' => ['Palu', 'Poso'],
            'Sulawesi Tenggara' => ['Kendari', 'Baubau'],
            'Sulawesi Utara' => ['Manado', 'Bitung'],
            'Sumatera Barat' => ['Padang', 'Bukittinggi'],
            'Sumatera Selatan' => ['Palembang', 'Lubuklinggau'],
            'Sumatera Utara' => ['Medan', 'Binjai', 'Pematangsiantar'],
        ];

        $data = [];
        foreach ($provinceCities as $provinceName => $cities) {
            $province = DB::table('provinces')->where('name', $provinceName)->first();
            if ($province) {
                foreach ($cities as $cityName) {
                    $data[] = [
                        'province_id' => $province->id,
                        'name' => $cityName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('cities')->insert($data);
    }
}
