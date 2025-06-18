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

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Bình luận đã được thêm.');
    }
}

