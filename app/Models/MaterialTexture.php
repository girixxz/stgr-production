<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialTexture extends Model
{
    protected $fillable = ['texture_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
