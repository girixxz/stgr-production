<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'design_variant_id',
        'sleeve_id',
        'size_id',
        'qty',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the order this item belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the design variant for this item
     */
    public function designVariant(): BelongsTo
    {
        return $this->belongsTo(DesignVariant::class, 'design_variant_id');
    }

    /**
     * Get the sleeve type for this item
     */
    public function sleeve(): BelongsTo
    {
        return $this->belongsTo(MaterialSleeve::class, 'sleeve_id');
    }

    /**
     * Get the size for this item
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(MaterialSize::class, 'size_id');
    }
}
