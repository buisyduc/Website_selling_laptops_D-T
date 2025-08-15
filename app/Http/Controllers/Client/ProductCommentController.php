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

            if (!auth()->check()) {
                $rules += [
                    'name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'email' => 'required|email|max:255'
                ];
            }

            $request->validate($rules);

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
            
            // Load lại relationship để có dữ liệu mới nhất
            $product->load('comments');
            
            // Trả về toàn bộ danh sách comments đã được cập nhật
            $comments = $product->comments;
            $commentsHtml = view('client.comments._list', compact('comments'))->render();

            return response()->json([
                'success' => true,
                'html' => $commentsHtml,
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
