<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorie extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'status', 'order'];

    public function children()
    {
        return $this->hasMany(categorie::class, 'parent_id')->orderBy('order', 'asc');
    }
      public function parent()
    {
        return $this->belongsTo(categorie::class, 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    
}
