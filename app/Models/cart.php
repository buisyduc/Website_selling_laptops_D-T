<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    // App\Models\Cart.php
    public function items()
    {
        return $this->hasMany(\App\Models\cart_item::class, 'cart_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function variants()
    {
        return $this->hasMany(product_variants::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
