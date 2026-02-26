<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\SoftDeletes;


#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    use HasFactory, Loggable, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'user_id',
        'quantity'
    ];

    protected $with = ['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
