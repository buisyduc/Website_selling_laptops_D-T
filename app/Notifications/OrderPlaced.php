<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $orderCode = $this->order->order_code ?? ('ORDER-' . $this->order->id);

        return [
            'type' => 'order_placed',
            'order_id' => $this->order->id,
            'order_code' => $orderCode,
            'user_id' => $this->order->user_id,
            'customer_name' => $this->order->customer_name ?? $this->order->name ?? 'Khách hàng',
            'total_amount' => $this->order->total_amount,
            'payment_method' => $this->order->payment_method,
            'message' => "Đơn hàng mới #{$orderCode} vừa được đặt.",
            'link' => route('admin.orders.show', $this->order->id),
        ];
    }
}
