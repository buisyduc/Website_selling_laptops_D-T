<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Bình luận đã được gửi!');
    }
public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không thể sửa bình luận của người khác.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update(['comment' => $request->comment]);

        return back()->with('success', '💬 Bình luận đã được cập nhật!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa bình luận của người khác.');
        }

        $comment->delete();

        return back()->with('success', '💬 Bình luận đã được xóa!');
    }
}
