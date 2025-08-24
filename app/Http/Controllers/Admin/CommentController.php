<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    // Danh sách bình luận (admin)
    public function index()
    {
        $comments = Comment::with(['user', 'product'])->latest()->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    // Xóa bình luận
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }

    // Lưu bình luận (hiện tại đã có)
    public function store(Request $request, $productId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Bình luận đã được gửi.');
    }
}
