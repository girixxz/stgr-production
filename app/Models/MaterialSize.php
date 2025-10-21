<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialSize extends Model
{
    protected $fillable = [
        'size_name',
        'extra_price',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
    ];

    /**
     * Get all order items using this size
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'size_id');
    }
}
