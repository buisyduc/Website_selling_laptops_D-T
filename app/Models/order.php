<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_code',
        'total_amount',
        'name',
        'phone',
        'address',
        'shipping_address',
        'note',
        'payment_method',
        'status',
    ];
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Đã hoàn thành',
            'canceled' => 'Đã hủy',
            default => ucfirst($this->status),
        };
    }
}
