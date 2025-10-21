<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    protected $fillable = [
        'district_id',
        'village_name',
    ];

    /**
     * Get the district this village belongs to
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Get all customers in this village
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'village_id');
    }
}
