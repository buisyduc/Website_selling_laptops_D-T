<?php

// app/Http/Controllers/Admin/ReviewController.php
namespace App\Http\Controllers\Admin;
use App\Models\order;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $orders = Order::withCount('reviews')
        ->with(['reviews.user', 'reviews.product', 'orderDetails.product'])
        ->has('reviews') // Chỉ lấy đơn hàng có đánh giá
        ->latest()
        ->paginate(15);
        
        return view('admin.reviews.index', compact('orders'));
        // $reviews = ProductReview::with(['user', 'product', 'order'])
        //     ->latest()
        //     ->paginate(15);
            
        // return view('admin.reviews.index', compact('reviews'));
    }
    public function destroy(ProductReview $review)
    {
        try {
        $review->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Xóa đánh giá thất bại: ' . $e->getMessage());
        }

    }
}