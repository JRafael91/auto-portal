<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderDetail;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uid',
        'info_date',
        'brand',
        'model',
        'year',
        'vehicle',
        'customer',
        'total',
        'comments',
        'status',
        'user_id',
        'technic_id'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(OrderImage::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technic(): BelongsTo
    {
        return $this->belongsTo(Technic::class);
    }

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('uid', 'DESC');
    }
}

