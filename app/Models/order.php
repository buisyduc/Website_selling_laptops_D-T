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
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isReviewable()
    {
        return $this->status === 'completed' 
            && $this->completed_at 
            && $this->completed_at->addDays(config('reviews.review_period_days', 30)) >= now();
    }

    public function canReviewProduct($productId)
    {
        // Kiểm tra đơn hàng đã hoàn thành
    if ($this->status !== 'completed') {
        return false;
    }

    // Kiểm tra sản phẩm có trong đơn hàng
    if (!$this->orderItems->contains('product_id', $productId)) {
        return false;
    }

    // Thêm điều kiện giới hạn số lần đánh giá nếu cần
    $reviewCount = $this->reviews()
        ->where('product_id', $productId)
        ->where('user_id', auth()->id())
        ->count();

    // giới hạn tối đa 3 đánh giá/sản phẩm/đơn hàng
    return $reviewCount < 3;
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function products()
    {
    // Thêm điều kiện giới hạn số lần đánh giá nếu cần
    $reviewCount = $this->reviews()
        ->where('product_id', $productId)
        ->where('user_id', auth()->id())
        ->count();

    // giới hạn tối đa 3 đánh giá/sản phẩm/đơn hàng
    return $reviewCount < 3;
    }
}
