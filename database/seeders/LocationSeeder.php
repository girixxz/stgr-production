<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Provinces
        $provinces = [
            ['id' => 1, 'province_name' => 'Jawa Tengah'],
            ['id' => 2, 'province_name' => 'Jawa Barat'],
            ['id' => 3, 'province_name' => 'Jawa Timur'],
            ['id' => 4, 'province_name' => 'DKI Jakarta'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }

        // Cities for Jawa Tengah
        $cities = [
            ['province_id' => 1, 'city_name' => 'Semarang'],
            ['province_id' => 1, 'city_name' => 'Surakarta'],
            ['province_id' => 1, 'city_name' => 'Magelang'],
            ['province_id' => 1, 'city_name' => 'Salatiga'],
            
            // Cities for Jawa Barat
            ['province_id' => 2, 'city_name' => 'Bandung'],
            ['province_id' => 2, 'city_name' => 'Bekasi'],
            ['province_id' => 2, 'city_name' => 'Bogor'],
            ['province_id' => 2, 'city_name' => 'Depok'],
            
            // Cities for Jawa Timur
            ['province_id' => 3, 'city_name' => 'Surabaya'],
            ['province_id' => 3, 'city_name' => 'Malang'],
            ['province_id' => 3, 'city_name' => 'Kediri'],
            ['province_id' => 3, 'city_name' => 'Madiun'],
            
            // Cities for DKI Jakarta
            ['province_id' => 4, 'city_name' => 'Jakarta Pusat'],
            ['province_id' => 4, 'city_name' => 'Jakarta Utara'],
            ['province_id' => 4, 'city_name' => 'Jakarta Selatan'],
            ['province_id' => 4, 'city_name' => 'Jakarta Timur'],
            ['province_id' => 4, 'city_name' => 'Jakarta Barat'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }

        // Districts for some cities
        $districts = [
            // Semarang
            ['city_id' => 1, 'district_name' => 'Semarang Tengah'],
            ['city_id' => 1, 'district_name' => 'Semarang Utara'],
            ['city_id' => 1, 'district_name' => 'Semarang Selatan'],
            ['city_id' => 1, 'district_name' => 'Semarang Timur'],
            ['city_id' => 1, 'district_name' => 'Semarang Barat'],
            
            // Bandung
            ['city_id' => 5, 'district_name' => 'Bandung Wetan'],
            ['city_id' => 5, 'district_name' => 'Bandung Kulon'],
            ['city_id' => 5, 'district_name' => 'Coblong'],
            ['city_id' => 5, 'district_name' => 'Cicendo'],
            
            // Surabaya
            ['city_id' => 9, 'district_name' => 'Gubeng'],
            ['city_id' => 9, 'district_name' => 'Wonokromo'],
            ['city_id' => 9, 'district_name' => 'Tegalsari'],
            ['city_id' => 9, 'district_name' => 'Genteng'],
            
            // Jakarta Pusat
            ['city_id' => 13, 'district_name' => 'Menteng'],
            ['city_id' => 13, 'district_name' => 'Gambir'],
            ['city_id' => 13, 'district_name' => 'Tanah Abang'],
            ['city_id' => 13, 'district_name' => 'Cempaka Putih'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }

        // Villages for some districts
        $villages = [
            // Semarang Tengah
            ['district_id' => 1, 'village_name' => 'Purwodinatan'],
            ['district_id' => 1, 'village_name' => 'Kauman'],
            ['district_id' => 1, 'village_name' => 'Gabahan'],
            
            // Semarang Utara
            ['district_id' => 2, 'village_name' => 'Tanjung Mas'],
            ['district_id' => 2, 'village_name' => 'Bandarharjo'],
            ['district_id' => 2, 'village_name' => 'Kuningan'],
            
            // Bandung Wetan
            ['district_id' => 6, 'village_name' => 'Citarum'],
            ['district_id' => 6, 'village_name' => 'Tamansari'],
            ['district_id' => 6, 'village_name' => 'Cihapit'],
            
            // Coblong
            ['district_id' => 8, 'village_name' => 'Lebak Siliwangi'],
            ['district_id' => 8, 'village_name' => 'Sadang Serang'],
            ['district_id' => 8, 'village_name' => 'Cipaganti'],
            
            // Gubeng
            ['district_id' => 10, 'village_name' => 'Airlangga'],
            ['district_id' => 10, 'village_name' => 'Barata Jaya'],
            ['district_id' => 10, 'village_name' => 'Gubeng'],
            
            // Menteng
            ['district_id' => 14, 'village_name' => 'Menteng'],
            ['district_id' => 14, 'village_name' => 'Pegangsaan'],
            ['district_id' => 14, 'village_name' => 'Cikini'],
        ];

        foreach ($villages as $village) {
            Village::create($village);
        }
    }
}
