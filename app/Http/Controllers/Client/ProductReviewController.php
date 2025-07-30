<?php

namespace App\Http\Controllers\Client;

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
        return view('client.reviews.create', [
            'order' => $order,
            'product' => $product,
            'maxImages' => config('reviews.max_images', 5)
        ]);
    }

    public function store(Request $request, Order $order, Product $product)
    {
        $validated = $this->validateReview($request);
        $images = $this->handleImageUpload($request);

        $review = $order->reviews()->create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'images' => $images,
            'is_approved' => config('reviews.auto_approve', false)
        ]);

        $product->updateAverageRating();
        event(new ProductReviewed($review));

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    public function show(ProductReview $review)
    {
        $review->load(['user', 'product']);
        return view('client.reviews.show', compact('review'));
    }

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
        $this->authorize('delete', $review);

        $product = $review->product;
        
        // Xóa ảnh đính kèm
        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::delete($image);
            }
        }
        
        $review->delete();
        $product->updateAverageRating();

        return back()->with('success', 'Đánh giá đã được xóa thành công!');
    }

    protected function validateReview(Request $request)
    {
        return $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string']
        ]);
    }

    protected function handleImageUpload(Request $request, array $currentImages = [])
    {
        $images = $currentImages;
        
        // Xóa ảnh được chọn
        if ($request->remove_images) {
            foreach ($request->remove_images as $image) {
                if (($key = array_search($image, $images)) !== false) {
                    Storage::delete($image);
                    unset($images[$key]);
                }
            }
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