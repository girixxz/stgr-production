<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'address',
    ];

    /**
     * Get the province this customer belongs to
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Get the city this customer belongs to
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the district this customer belongs to
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Get the village this customer belongs to
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    /**
     * Get all orders for this customer
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
