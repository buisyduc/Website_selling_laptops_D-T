<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;
use App\Models\cart_item;


class User extends Authenticatable
{


    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'address',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];




    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function cartItems()
    {
        return $this->hasManyThrough(cart_item::class, Cart::class, 'user_id', 'cart_id', 'id', 'id');
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function hasPurchasedProduct($productId)
    {
        return $this->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }
    public function canReviewProduct($orderItem)
{
    // Kiểm tra xem người dùng có phải chủ đơn hàng không
    if ($orderItem->order === null || $orderItem->order->user_id !== $this->id) {
    return false;
}

    // Kiểm tra đơn hàng đã hoàn thành chưa
    if ($orderItem->order->status !== 'hoan_thanh') {
        return false;
    }

    // Kiểm tra đã đánh giá sản phẩm này trong đơn hàng này chưa
    $alreadyReviewed = \App\Models\ProductReview::where('user_id', $this->id)
        ->where('product_id', $orderItem->product_id)
        ->where('order_id', $orderItem->order_id)
        ->exists();

    return !$alreadyReviewed;
}
}
