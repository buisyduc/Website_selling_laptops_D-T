<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variant_attributes extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id'];
 public function children()
{
    return $this->hasMany(variant_attributes::class, 'parent_id');
}

public function parent()
{
    return $this->belongsTo(variant_attributes::class, 'parent_id');
}


    public function options()
    {
        return $this->hasMany(variant_options::class, 'attribute_id');
    }
     public function variantOptions()
    {
        return $this->hasMany(product_variant_options::class, 'attribute_id');
    }
}
