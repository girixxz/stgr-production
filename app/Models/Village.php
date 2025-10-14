<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['district_id', 'village_name'];

    // 1 village belongs to 1 district
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // 1 village punya banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
