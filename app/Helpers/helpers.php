<?php

if (!function_exists('getOrderStatusLabel')) {
    function getOrderStatusLabel($status)
    {
        return match ($status) {
            'pending'   => '⏳ Chờ xử lý',
            'processing_seller' => 'Xác nhận ✅',
            'processing' => 'giao hàng 🚚',
            'shipping' => 'giao hàng thành công 📦',
            'completed' => 'Hoàn Thành',
            'cancelled' => 'Đã Hủy',
            'canceled' => 'Đã Hủy',
            'returned' => 'Hoàn Tiền',
            'paid'      => '✅ Đã thanh toán',
            'failed'    => '❌ Thất bại',
            'cancelled' => '🚫 Đã hủy',
            'completed' => '🎉 Hoàn tất',
            default     => '❔ Không xác định',
        };
    }
}
