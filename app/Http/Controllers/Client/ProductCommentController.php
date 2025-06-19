<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\Comment;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function store(Request $request, product $product)
    {
        $request->validate([
            'content' => 'required|min:5|max:1000',
        ]);

        $product->comments()->create([
            'user_name' => $request->input('user_name'),
            'content' => $request->input('content'),
            'is_active' => true, // mặc định hiển thị
        ]);

        return redirect()->back()->with('success', 'Đã bình luận thành công!');
    }
}

