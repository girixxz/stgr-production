<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_no',
        'total_bill',
        'amount_paid',
        'amount_due',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_bill' => 'integer',
        'amount_paid' => 'integer',
        'amount_due' => 'integer',
    ];

    // 🔗 Relasi

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
