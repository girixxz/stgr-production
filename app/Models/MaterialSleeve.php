<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialSleeve extends Model
{
    // kasih tau nama tabel yang benar
    protected $table = 'material_sleeves';
    protected $fillable = ['sleeve_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
