<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variant_options extends Model
{
    use HasFactory;
    protected $fillable = ['attribute_id', 'value'];

    public function attribute()
    {
        return $this->belongsTo(variant_attributes::class);
    }
     public function variantOptions()
    {
        return $this->hasMany(product_variant_options::class, 'option_id');
    }
    
}
