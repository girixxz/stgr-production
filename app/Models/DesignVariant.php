<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesignVariant extends Model
{
    use HasFactory;

    // kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'order_id',
        'design_name',
    ];

    // 🔗 Relasi

    // setiap varian desain dimiliki oleh 1 order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // setiap varian desain bisa punya banyak item (ukuran, lengan, dll.)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
