<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id', 'city_name'];

    // 1 city belongs to 1 province
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // 1 city punya banyak district
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // 1 city punya banyak village melalui district
    public function villages()
    {
        return $this->hasManyThrough(Village::class, District::class);
    }

    // 1 city punya banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
