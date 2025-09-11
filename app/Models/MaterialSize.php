<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialSize extends Model
{
    protected $table =  'material_sizes';
    protected $fillable = ['size_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
