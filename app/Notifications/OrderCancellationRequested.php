<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancellationRequested extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $event = 'order_canceled') {}

    public function via($notifiable)
    {
        // Có thể thêm 'mail' sau nếu muốn gửi email
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $orderCode = $this->order->order_code ?? ('ORDER-' . $this->order->id);

        // Xây dựng message theo loại sự kiện
        $method = strtolower(trim((string)($this->order->payment_method ?? '')));
        $status = strtolower(trim((string)($this->order->payment_status ?? '')));
        $orderStatus = strtolower(trim((string)($this->order->status ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $onlineStatuses = ['paid', 'refund_pending', 'returned_refunded', 'refunded', 'partially_refunded'];
        $isOnlinePaid = ($method !== '' && !in_array($method, $codKeywords, true)) || in_array($status, $onlineStatuses, true);
       $message = match ($this->event) {
    'order_canceled'   => "Khách hàng đã hủy đơn hàng #{$orderCode}.",

    'refund_requested' => match (true) {
        // Đơn online (VNPAY) và còn pending → hủy đơn + hoàn tiền
        $orderStatus === 'pending' && $method === 'vnpay' =>
            "Khách hàng muốn hủy đơn hoàn tiền cho đơn hàng #{$orderCode}.",

        // Đơn đã xử lý + thanh toán online → hủy + hoàn tiền
        $orderStatus !== 'pending' && $isOnlinePaid =>
            "Khách hàng muốn hủy đơn hoàn tiền cho đơn hàng #{$orderCode}.",

        // COD hoặc các trường hợp khác → chỉ hoàn tiền
        default =>
            "Khách hàng yêu cầu hoàn tiền cho đơn hàng #{$orderCode}.",
    },

    'refund_canceled'  => "Khách hàng đã hủy yêu cầu hoàn tiền cho đơn hàng #{$orderCode}.",

    'return_requested' => $isOnlinePaid
        ? "Khách hàng yêu cầu trả hàng hoàn tiền cho đơn hàng #{$orderCode}."
        : "Khách hàng yêu cầu trả hàng cho đơn hàng #{$orderCode}.",

    'return_refund_requested' => $isOnlinePaid
        ? "Khách hàng yêu cầu trả hàng hoàn tiền cho đơn hàng #{$orderCode}."
        : "Khách hàng yêu cầu trả hàng cho đơn hàng #{$orderCode}.",

    'return_canceled'  => $isOnlinePaid
        ? "Khách hàng đã hủy yêu cầu trả hàng hoàn tiền cho đơn hàng #{$orderCode}."
        : "Khách hàng đã hủy yêu cầu trả hàng cho đơn hàng #{$orderCode}.",

    'return_refund_canceled'  => $isOnlinePaid
        ? "Khách hàng đã hủy yêu cầu trả hàng hoàn tiền cho đơn hàng #{$orderCode}."
        : "Khách hàng đã hủy yêu cầu trả hàng cho đơn hàng #{$orderCode}.",

    // Khách hàng YÊU CẦU hủy yêu cầu trả hàng (client gửi yêu cầu hủy)
    'return_cancel_requested' => $isOnlinePaid
        ? "Khách hàng yêu cầu hủy yêu cầu trả hàng hoàn tiền cho đơn hàng #{$orderCode}."
        : "Khách hàng yêu cầu hủy yêu cầu trả hàng cho đơn hàng #{$orderCode}.",

    // Khi khách xác nhận đã nhận hàng
    'delivered_confirmed' => "Khách hàng đã xác nhận đã nhận đơn hàng #{$orderCode}.",

    default => "Khách hàng đã hủy đơn hàng #{$orderCode}.",
};


        return [
            'type' => $this->event,
            'order_id' => $this->order->id,
            'order_code' => $orderCode,
            'user_id' => $this->order->user_id,
            'customer_name' => $this->order->customer_name ?? $this->order->name ?? 'Khách hàng',
            'message' => $message,
            // Điều chỉnh route theo hệ thống admin của bạn
            'link' => route('admin.orders.show', $this->order->id),
        ];
    }
}
