<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
            'name',
            'slug',
            'description',
            'image',
            'category_id',
            'brand_id',
            'release_date',
            'status' => 'boolean',
            'release_date' => 'date'

    ];
    protected $casts = [
    'release_date' => 'date',  // hoặc 'datetime' nếu có giờ
    ];

    public function category()
    {
        return $this->belongsTo(categories::class);
    }
    public function brand()
    {
        return $this->belongsTo(brand::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0; // Nếu không có đánh giá, trả về 0
    }
    public function orders()
    {
        return $this->belongsToMany(order::class)
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
    public function orderItems()
    {
        return $this->hasMany(order_item::class, 'product_id');
    }

    public function totalSold()
    {
        return $this->orderItems()->sum('quantity');
    }
    public function images()
    {
        return $this->hasMany(product_image::class);
    }
    public function variants()
    {
        return $this->hasMany(product_variants::class);
    }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_product');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->approved();
    }

    public function pendingReviews()
    {
        return $this->hasMany(ProductReview::class)->pending();
    }

    public function updateAverageRating()
    {
        $this->average_rating = round($this->reviews()->avg('rating'), 1); // VD: 4.9
        $this->reviews_count  = $this->reviews()->count();                 // VD: 118
        $this->save();
    }

}
