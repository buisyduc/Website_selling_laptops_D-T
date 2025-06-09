<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
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
        return $this->belongsTo(categorie::class);
    }
    public function brand()
    {
        return $this->belongsTo(brand::class);
    }
    public function reviews()
    {
        return $this->hasMany(review::class, 'product_id');
    }
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0; // Nếu không có đánh giá, trả về 0
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
}
