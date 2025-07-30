<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $products = product::withCount('comments')->get();
        return view('admin.Comments.index', compact('products'));
    }
    public function show($productId)
    {
        $product = product::with('comments')->findOrFail($productId);
        return view('admin.Comments.show', compact('product'));
    }
    // hàm ẩn-hiển thị
    public function toggleVisibility(Comment $comment)
    {
        // nhấn nút ẩn -hiển thị
        $comment->is_active = !$comment->is_active;
        $comment->save();// thì bl sẽ lưu

        return back()->with('success', 'Trạng thái bình luận đã được cập nhật.');
    }
}
