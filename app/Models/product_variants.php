<?php

namespace App\Models;
use App\Models\variant_attributes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_variants extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'price',
        'stock_quantity',
        'sku',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantOptions()
    {
        return $this->hasMany(product_variant_options::class, 'variant_id');
    }

    public function getAttributesAttribute()
    {
        return $this->variantOptions()->with(['attribute', 'option'])->get();
    }
     public function variantAttribute()
    {
        return $this->belongsTo(variant_attributes::class, 'variant_attribute_id');
    }
    public function options()
{
    return $this->hasMany(product_variant_options::class, 'variant_id');
}

}
