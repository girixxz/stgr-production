<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'province_id',
        'city_name',
    ];

    /**
     * Get the province this city belongs to
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Get all districts in this city
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'city_id');
    }

    /**
     * Get all customers in this city
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'city_id');
    }
}
