<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['city_id', 'district_name'];

    // 1 district belongs to 1 city
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // 1 district punya banyak village
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    // 1 district punya banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
