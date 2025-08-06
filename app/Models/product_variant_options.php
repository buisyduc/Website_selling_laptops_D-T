<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_variant_options extends Model
{
    use HasFactory;
    protected $fillable = [
        'variant_id',
        'attribute_id',
        'option_id'
    ];

    public function variant()
    {
        return $this->belongsTo( product_variants::class);
    }

    public function attribute()
    {
        return $this->belongsTo(variant_attributes::class);
    }

    public function option()
    {
        return $this->belongsTo(variant_options ::class);
    }
    
}
