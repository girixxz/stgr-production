<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'design_variant_id',
        'sleeve_id',
        'size_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function designVariant()
    {
        return $this->belongsTo(DesignVariant::class);
    }

    public function sleeve()
    {
        return $this->belongsTo(MaterialSleeve::class);
    }

    public function size()
    {
        return $this->belongsTo(MaterialSize::class);
    }
}
