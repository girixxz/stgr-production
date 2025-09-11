<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name'];

    // 1 province punya banyak city
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // 1 province punya banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
