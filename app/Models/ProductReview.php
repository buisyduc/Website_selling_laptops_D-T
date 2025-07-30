<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'images',
        'is_approved',
        'is_edited'
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'is_edited' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Khách hàng ẩn danh'
        ]);
    }

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(order::class);
    }

    // Scope đánh giá đã phê duyệt
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope đánh giá chưa phê duyệt
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Format rating
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    // Lấy ảnh đầu tiên
    public function getFirstImageAttribute()
    {
        return $this->images ? Storage::url($this->images[0]) : null;
    }

    // Kiểm tra có thể chỉnh sửa
    public function canBeEdited()
    {
        return $this->created_at->addDays(3) > now();
    }
}

