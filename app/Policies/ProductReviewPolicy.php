<?php

// app/Policies/ProductReviewPolicy.php

namespace App\Policies;

use App\Models\order;
use App\Models\product;
use App\Models\ProductReview;
use App\Models\User;

class ProductReviewPolicy
{
    // Kiểm tra user có thể đánh giá sản phẩm trong đơn hàng không
    public function review(User $user, order $order, product $product)
    {
        // Chỉ user đặt hàng mới được đánh giá
        if ($order->user_id !== $user->id) {
            return false;
        }
        
        // Đơn hàng phải ở trạng thái hoàn thành
        if (!$order->isReviewable()) {
            return false;
        }
        
        // Sản phẩm phải có trong đơn hàng
        if (!$order->canReviewProduct($product->id)) {
            return false;
        }
        
        return true;
    }

    // Kiểm tra user có thể chỉnh sửa đánh giá không
    public function update(User $user, ProductReview $review)
    {
        return $user->id === $review->user_id && $review->canBeEdited();
    }

    // Kiểm tra user có thể xóa đánh giá không
    public function delete(User $user, ProductReview $review)
    {
        return $user->id === $review->user_id || $user->hasRole('admin');
    }
}
