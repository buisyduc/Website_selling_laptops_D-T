<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\Comment;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function store(Request $request, Product $product)
{
    // Kiểm tra CSRF token
    if (!hash_equals($request->session()->token(), $request->input('_token'))) {
        return response()->json([
            'success' => false,
            'message' => 'Token không hợp lệ'
        ], 419);
    }

    try {
        $rules = [
            'content' => 'required|string',
            'post_id' => 'required|exists:products,id'
        ];

        // Thêm rules cho khách
        if (!auth()->check()) {
            $rules += [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255'
            ];
        }

        $request->validate($rules);

        // Tạo comment
        $commentData = [
            'content' => $request->input('content'),
            'product_id' => $product->id,
            'is_active' => true
        ];

        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            $commentData += [
                'guest_name' => $request->input('name'),
                'guest_phone' => $request->input('phone'),
                'guest_email' => $request->input('email')
            ];
        }

        $comment = Comment::create($commentData);

        return response()->json([
            'success' => true,
            'html' => view('client.comments._item', compact('comment'))->render(),
            'message' => 'Bình luận thành công!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
