<?php

namespace App\Traits;

use App\Models\InventoryLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Loggable
{
    public function log(): MorphMany
    {
        return $this->morphMany(InventoryLog::class, 'model');
    }
}