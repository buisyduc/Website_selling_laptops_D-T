<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class categories extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'status', 'order'];
    protected $dates = ['deleted_at']; // Khai báo cột deleted_at là kiểu ngày tháng

    public function children()
    {
        return $this->hasMany(categories::class, 'parent_id')->orderBy('order', 'asc');
    }
      public function parent()
    {
        return $this->belongsTo(categories::class, 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function getBreadcrumbs()
        {
            $breadcrumbs = collect();
            $category = $this;

            while ($category) {
                $breadcrumbs->prepend($category);
                $category = $category->parent;
            }

            return $breadcrumbs;
        }

}
