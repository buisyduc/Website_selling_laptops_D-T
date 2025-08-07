<?php

// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'product_id',
        'is_active',
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getCommenterName()
    {
        return $this->user_id ? $this->user->name : $this->guest_name;
    }

    public function getCommenterInfo()
    {
        if ($this->user_id) {
            return [
                'name' => $this->user->name,
                'email' => $this->user->email,
                // Thêm các thông tin khác từ user nếu cần
            ];
        }

        return [
            'name' => $this->guest_name,
            'email' => $this->guest_email,
            'phone' => $this->guest_phone,
            'gender' => $this->guest_gender
        ];
    }
}