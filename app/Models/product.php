<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'category_id',
        'brand_id',
        'release_date',
        'status'
    ];

    protected $casts = [
        'release_date' => 'date',
        'status' => 'boolean'
    ];

    // 🔗 Danh mục (bao gồm cả khi bị soft delete nếu muốn gọi thủ công)
    public function category()
    {
        return $this->belongsTo(categorie::class)->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(review::class, 'product_id');
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
        $this->update([
            'average_rating' => $this->approvedReviews()->avg('rating'),
            'review_count' => $this->approvedReviews()->count()
        ]);
    }
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function orderItems()
    {
        return $this->hasMany(order_item::class, 'product_id');
    }
     public function orders()
    {
        return $this->belongsToMany(order::class)
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
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

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // ✅ Tự động lọc các sản phẩm có danh mục đã bị soft delete
    protected static function booted()
    {
        static::addGlobalScope('hasActiveCategory', function (Builder $builder) {
            $builder->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            });
        });
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    
}
