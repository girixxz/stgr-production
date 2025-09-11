<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    // kasih tau nama tabel yang benar
    protected $table = 'product_categories';

    protected $fillable = ['product_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
