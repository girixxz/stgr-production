<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'province_name',
    ];

    /**
     * Get all cities in this province
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_id');
    }

    /**
     * Get all customers in this province
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'province_id');
    }
}
