<?php

// app/Console/Commands/ApprovePendingReviews.php

namespace App\Console\Commands;

use App\Models\ProductReview;
use Illuminate\Console\Command;

class ApprovePendingReviews extends Command
{
    protected $signature = 'reviews:approve-pending';
    protected $description = 'Tự động phê duyệt các đánh giá chờ sau 24 giờ';

    public function handle()
    {
        $count = ProductReview::pending()
            ->where('created_at', '<=', now()->subDay())
            ->update(['is_approved' => true]);
            
        $this->info("Đã phê duyệt {$count} đánh giá chờ.");
        return 0;
    }
}