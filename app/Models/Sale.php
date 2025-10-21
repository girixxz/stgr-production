<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'sales_name',
        'phone',
    ];

    /**
     * Get all orders for this sales person
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'sales_id');
    }
}
