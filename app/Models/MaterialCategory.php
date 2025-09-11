<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    protected $fillable = ['material_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
