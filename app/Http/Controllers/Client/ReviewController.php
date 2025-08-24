<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Models\product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id'   => Auth::id(),
            'product_id' => $productId,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
        ]);

        // Tính trung bình rating sau khi thêm review mới
        $product = product::with('reviews')->findOrFail($productId);
        $averageRating = round($product->reviews()->avg('rating') ?? 0, 1);

        // Đưa về lại trang sản phẩm kèm theo rating mới
        return redirect()
            ->back()
            ->with('success', 'Đánh giá của bạn đã được gửi!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không thể sửa đánh giá của người khác.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($request->only('rating', 'comment'));

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }
    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không thể chỉnh sửa đánh giá của người khác.');
        }

        return view('client.reviews.edit', compact('review'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa đánh giá của người khác.');
        }

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa!');
    }
}
