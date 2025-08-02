<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Events\ProductReviewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    // Số ngày cho phép chỉnh sửa đánh giá
    const EDITABLE_DAYS = 3;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        
        // Sử dụng Gate để kiểm tra quyền
        // $this->middleware(function ($request, $next) {
        //     if ($request->routeIs('reviews.create') || $request->routeIs('reviews.store')) {
        //         $order = $request->route('order');
        //         $product = $request->route('product');
                
        //         if (!Gate::allows('review', [$order, $product])) {
        //             abort(403, 'Bạn không có quyền đánh giá sản phẩm này');
        //         }
        //     }
            
        //     return $next($request);
        // })->only(['create', 'store']);
    }

    public function index(Request $request, Product $product)
    {
        $reviews = $product->approvedReviews()
            ->with(['user', 'product'])
            ->latest()
            ->paginate($request->input('per_page', 10));

        return view('client.reviews.index', compact('product', 'reviews'));
    }

    public function create(Order $order, Product $product)
    {
        return view('client.reviews.create', compact('order', 'product'));
    }

    public function store(Request $request, Order $order, Product $product)
    {
        try {
            // Kiểm tra giới hạn review trước khi tạo
            $reviewsToday = ProductReview::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count();
                if ($reviewsToday >= 10) {
                return redirect()->back()
                    ->with('error', 'Bạn chỉ được đánh giá tối đa 10 lần/ngày')
                    ->withInput();
            }

             $validated = $this->validateReview($request);
            
            // Xử lý upload ảnh
            $images = $this->handleImageUpload($request); 
            
            // Tạo review
            $review = $order->reviews()->create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'images' => $images,
                'is_approved' => config('reviews.auto_approve', false),
            ]);
            
            // Cập nhật rating trung bình
            $product->updateAverageRating();
            
            // Gửi thông báo
            event(new \App\Events\ProductReviewed($review));
            
            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Đánh giá của bạn đã được gửi thành công!');
                
        } catch (\Exception $e) {
            \Log::error('Review submission error: '.$e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage())->withInput();
        }
    }

    // public function show(ProductReview $review)
    // {
    //     $review->load(['user', 'product']);
    //     return view('client.reviews.show', compact('review'));
    // }

    public function edit(ProductReview $review)
    {
        $this->authorize('update', $review);

        if (!$this->isEditable($review)) {
            return redirect()->back()
                ->with('error', 'Bạn chỉ có thể chỉnh sửa đánh giá trong vòng '.self::EDITABLE_DAYS.' ngày');
        }

        return view('client.reviews.edit', [
            'review' => $review,
            'maxImages' => config('reviews.max_images', 5)
        ]);
    }

    public function update(Request $request, ProductReview $review)
    {
        $this->authorize('update', $review);

        if (!$this->isEditable($review)) {
            return redirect()->back()
                ->with('error', 'Bạn chỉ có thể chỉnh sửa đánh giá trong vòng '.self::EDITABLE_DAYS.' ngày');
        }

        $validated = $this->validateReview($request);
        $images = $this->handleImageUpload($request, $review->images);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'images' => $images,
            'is_edited' => true,
            'is_approved' => $review->is_approved && config('reviews.reapprove_after_edit', false) 
                ? false 
                : $review->is_approved
        ]);

        $review->product->updateAverageRating();

        return redirect()->route('products.show', $review->product)
            ->with('success', 'Đánh giá của bạn đã được cập nhật!');
    }

    public function destroy(ProductReview $review)
    {
        // Lưu lại order_id trước khi xóa
        $orderId = $review->order_id;
        
        // Xóa ảnh đính kèm
        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::delete($image);
            }
        }

        $product = $review->product;
        $review->delete();

        // Cập nhật rating trung bình
        $product->updateAverageRating();

        // Chuyển hướng về trang chi tiết đơn hàng cụ thể
        return redirect()->route('client.orders.show', $orderId)
            ->with('success', __('Đánh giá đã được xóa thành công!'));
    }

    protected function validateReview(Request $request)
    {
        return $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048',
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string']
        ]);
    }

    protected function handleImageUpload(Request $request, array $currentImages = [])
    {
        $images = $currentImages;
        
         $images = $currentImages;
    
    // Xóa ảnh được chọn
    if ($request->has('remove_images')) {
        foreach ($request->remove_images as $imageToRemove) {
            // Kiểm tra xem ảnh có tồn tại trong mảng hiện tại không
            if (in_array($imageToRemove, $images)) {
                // Xóa ảnh từ storage
                Storage::delete($imageToRemove);

                // Xóa ảnh khỏi mảng
                $images = array_filter($images, function($image) use ($imageToRemove) {
                    return $image !== $imageToRemove;
                });
            }
        }
        // Đảm bảo mảng được đánh lại index
        $images = array_values($images);
    }
    
    // Thêm ảnh mới
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if (count($images) >= config('reviews.max_images', 5)) break;
            
            $path = $image->store('reviews');
            $images[] = $path;
        }
    }
    
    return !empty($images) ? $images : null;
    }

    protected function isEditable(ProductReview $review)
    {
        return $review->created_at->diffInDays(now()) <= self::EDITABLE_DAYS;
    }
}