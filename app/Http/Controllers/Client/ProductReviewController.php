<?php

// app/Http/Controllers/ProductReviewController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductReviewController extends Controller
{
    // Middleware
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        // $this->middleware('auth')->except(['index', 'show']);
        // $this->middleware('can:review,order,product')->only(['create', 'store']);
        // $this->middleware('can:update,review')->only(['edit', 'update']);
        // $this->middleware('can:delete,review')->only('destroy');
    }

    // Danh sách đánh giá (API)
    public function index(Request $request, Product $product)
    {
        $reviews = $product->approvedReviews()
            ->with(['user', 'product'])
            ->latest()
            ->paginate($request->input('per_page', 10));
            
        return response()->json([
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'last_page' => $reviews->lastPage()
            ]
        ]);
    }

    // Form tạo đánh giá
    public function create(Order $order, Product $product)
    {
        return view('client.reviews.create', compact('order', 'product'));
    }

    // Lưu đánh giá
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
    

    // Hiển thị chi tiết đánh giá
    public function show(ProductReview $review)
    {
        return view('client.reviews.show', compact('review'));
    }

    // Form chỉnh sửa đánh giá
    public function edit(ProductReview $review)
    {
        if (!$review->canBeEdited()) {
            return back()->with('error', __('Bạn chỉ có thể chỉnh sửa đánh giá trong vòng 3 ngày'));
        }
        
        return view('client.reviews.edit', compact('review'));
    }

    // Cập nhật đánh giá
    public function update(Request $request, ProductReview $review)
    {   
        $validated = $this->validateReview($request);
        
        // Xử lý ảnh
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
        
        // Cập nhật rating trung bình
        $review->product->updateAverageRating();
        
        // Thống nhất chuyển hướng về trang đơn hàng
        return redirect()->route('client.orders.show', $review->order_id)
            ->with('success', __('Đánh giá của bạn đã được cập nhật!'));
    }

    // Xóa đánh giá
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

    // Validate đánh giá
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

    // Xử lý upload ảnh
    protected function handleImageUpload(Request $request, array $currentImages = [])
{
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
}
