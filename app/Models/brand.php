<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =[
        'name',
        'slug',
        'description',
        'logo',
    ];
      protected $dates = ['deleted_at']; // Khai báo cột deleted_at là kiểu ngày tháng
}
