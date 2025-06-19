<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_percent',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'expires_at',
    ];

    protected $casts = [
        'discount_percent' => 'float',
        'max_discount' => 'float',
        'min_order_amount' => 'float',
        'expires_at' => 'datetime',
    ];
    public function products()
{
    return $this->belongsToMany(Product::class, 'coupon_product');
}
public function orders()
{
    return $this->hasMany(Order::class);
}
public function timesUsedByUser($userId)
{
    return $this->orders()->where('user_id', $userId)->count();
}

}
