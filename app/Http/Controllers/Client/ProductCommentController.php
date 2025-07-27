<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\Comment;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function store(Request $request, product $product, $productId)
    {
        try {
            $request->validate([
                'content' => 'required|string',
            ]);

            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập'
                ], 401);
            }

            $product = Product::findOrFail($productId);

            $comment = $product->comments()->create([
                'content' => $request->content,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'html' => view('client.comments._item', compact('comment'))->render(),
                'message' => 'Bình luận đã được thêm thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
}
