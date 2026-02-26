<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryLog extends Model
{
    protected $fillable = [
        'event',
        'model_type',
        'model_id',
        'user_id',
        'before',
        'after',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo('model');
    }
}
