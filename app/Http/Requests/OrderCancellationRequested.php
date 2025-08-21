<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancellationRequested extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $type = 'cancel_requested') {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $orderCode = $this->order->order_code ?? ('ORDER-'.$this->order->id);

        $message = match ($this->type) {
            'refund_requested'       => "Khách hàng đã gửi yêu cầu hoàn tiền cho đơn hàng #{$orderCode}",
            'cancel_requested'       => "Khách hàng đã gửi yêu cầu hủy đơn hàng #{$orderCode}",
            'cancel_refund_requested'=> "Khách hàng đã gửi yêu cầu hủy đơn hoàn tiền cho đơn hàng #{$orderCode}",
            'delivered_confirmed' => "Khách hàng đã xác nhận đã nhận hàng cho đơn #{$orderCode}",
            default                  => "Khách hàng đã gửi yêu cầu cho đơn hàng #{$orderCode}",
        };

        return [
            'type'          => $this->type,
            'order_id'      => $this->order->id,
            'order_code'    => $orderCode,
            'user_id'       => $this->order->user_id,
            'customer_name' => $this->order->customer_name ?? $this->order->name ?? 'Khách hàng',
            'message'       => $message,
            'link'          => route('admin.orders.show', $this->order->id),
        ];
    }
}
