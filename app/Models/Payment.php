<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'paid_at',
        'payment_method',
        'payment_type',
        'amount',
        'notes',
        'img_url',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'integer',
    ];

    // 🔗 Relasi

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
