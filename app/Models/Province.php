<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['province_name'];

    // 1 province punya banyak city
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // 1 province punya banyak district melalui city
    public function districts()
    {
        return $this->hasManyThrough(District::class, City::class);
    }

    // 1 province punya banyak village melalui city dan district
    public function villages()
    {
        return $this->hasManyThrough(Village::class, City::class, 'province_id', 'district_id', 'id', 'city_id')
            ->join('districts', 'districts.city_id', '=', 'cities.id');
    }

    // 1 province punya banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
