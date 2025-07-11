<?php

// app/Listeners/SendProductReviewNotification.php

namespace App\Listeners;

use App\Events\ProductReviewed;
use App\Notifications\NewProductReview;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProductReviewNotification implements ShouldQueue
{
    public function handle(ProductReviewed $event)
    {
        // Gửi thông báo cho admin
        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            $admin->notify(new NewProductReview($event->review));
        }
        
        // Gửi thông báo cho chủ shop (nếu có)
        if ($event->review->product->user) {
            $event->review->product->user->notify(new NewProductReview($event->review));
        }
    }
}
