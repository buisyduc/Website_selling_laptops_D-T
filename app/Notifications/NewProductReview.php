<?php

// app/Notifications/NewProductReview.php

namespace App\Notifications;

use App\Models\ProductReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProductReview extends Notification implements ShouldQueue
{
    use Queueable;

    public $review;

    public function __construct(ProductReview $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[Thông báo] Có đánh giá mới: ' . $this->review->product->name)
            ->greeting('Xin chào!')
            ->line('Khách hàng ' . $this->review->user->name . ' vừa đánh giá sản phẩm ' . $this->review->product->name)
            ->line('Xếp hạng: ' . str_repeat('⭐', $this->review->rating))
            ->action('Xem đánh giá', url('/admin/reviews/' . $this->review->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Có đánh giá mới cho sản phẩm ' . $this->review->product->name,
            'rating' => $this->review->rating,
            'review_id' => $this->review->id,
            'product_id' => $this->review->product_id,
            'url' => '/admin/reviews/' . $this->review->id,
        ];
    }
}
