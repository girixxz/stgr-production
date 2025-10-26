<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionStage extends Model
{
    protected $fillable = [
        'stage_name',
    ];

    /**
     * Get all order stages using this production stage
     */
    public function orderStages(): HasMany
    {
        return $this->hasMany(OrderStage::class, 'stage_id');
    }
}
