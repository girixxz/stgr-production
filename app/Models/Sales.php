<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
    protected $fillable = ['sales_name', 'phone_number'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
