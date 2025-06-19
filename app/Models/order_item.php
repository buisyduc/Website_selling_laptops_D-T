<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    use HasFactory;
    protected $fillable=[
        'order_id','product_id','quantity','variant_id','product_name','price',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
     public function variant()
    {
        return $this->belongsTo(product_variants::class, 'variant_id');
    }
}
