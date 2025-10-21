<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'city_id',
        'district_name',
    ];

    /**
     * Get the city this district belongs to
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get all villages in this district
     */
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'district_id');
    }

    /**
     * Get all customers in this district
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'district_id');
    }
}
