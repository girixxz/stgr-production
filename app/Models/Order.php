<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'priority',
        'customer_id',
        'sales_id',
        'order_name',
        'order_date',
        'due_date',
        'product_category_id',
        'product_color',
        'material_category_id',
        'material_texture_id',
        'notes',
        'shipping_id',
        'total_qty',
        'sub_total',
        'discount',
        'grand_total',
        'production_status',
    ];

    // casting otomatis ke Carbon
    protected $casts = [
        'order_date' => 'datetime',
        'due_date'   => 'datetime',
    ];

    // 🔗 Relasi

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function materialCategory()
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    public function materialTexture()
    {
        return $this->belongsTo(MaterialTexture::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function designVariants()
    {
        return $this->hasMany(DesignVariant::class);
    }

    // 🔥 tambahan kalau order_items punya order_id langsung
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function extraServices()
    {
        return $this->hasMany(ExtraService::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
