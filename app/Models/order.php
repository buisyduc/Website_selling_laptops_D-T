<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'coupon_id',
        'order_code',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'shipping_method',
        'shipping_fee',
        'discount_amount',
        'note',
        'expected_delivery_date',
        'confirmed_at',
        'shipped_at',
        'province',
        'district',
        'ward',
        'address',
        'payment_transaction_id',
        'payment_redirect_url',
        'payment_response_data',
        'delivered_at',
        'cancelled_at',

    ];
    public function items()
    {
        return $this->hasMany(order_item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function coupon()
    {
        return $this->belongsTo(\App\Models\Coupon::class);
    }
    public function orderItems()
    {
        return $this->hasMany(order_item::class);
    }
}
