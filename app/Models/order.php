<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id',
        'coupon_id',
        'order_code',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'note',
    ];
     public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
