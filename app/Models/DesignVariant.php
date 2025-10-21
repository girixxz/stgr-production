<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignVariant extends Model
{
    protected $fillable = [
        'order_id',
        'design_name',
    ];

    /**
     * Get the order this design variant belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get all order items for this design variant
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'design_variant_id');
    }
}
