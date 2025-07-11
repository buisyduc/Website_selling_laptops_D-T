<?php

return [
    // Tự động phê duyệt đánh giá
    'auto_approve' => env('REVIEWS_AUTO_APPROVE', false),
    
    // Số ngày cho phép đánh giá sau khi đơn hàng hoàn thành
    'review_period_days' => env('REVIEWS_PERIOD_DAYS', 30),
    
    // Số ảnh tối đa mỗi đánh giá
    'max_images' => env('REVIEWS_MAX_IMAGES', 5),
    
    // Kích thước ảnh tối đa (KB)
    'image_size' => env('REVIEWS_IMAGE_SIZE', 2048),
    
    // Phê duyệt lại sau khi chỉnh sửa
    'reapprove_after_edit' => env('REVIEWS_REAPPROVE_AFTER_EDIT', false),
    
    // Thời gian cho phép chỉnh sửa (ngày)
    'editable_period_days' => env('REVIEWS_EDITABLE_DAYS', 3),
];