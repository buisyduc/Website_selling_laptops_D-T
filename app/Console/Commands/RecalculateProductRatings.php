<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class RecalculateProductRatings extends Command
{
    /**
     * Tên lệnh dùng để chạy.
     *
     * @var string
     */
    protected $signature = 'products:recalculate-ratings';

    /**
     * Mô tả lệnh.
     *
     * @var string
     */
    protected $description = 'Recalculate average rating and reviews count for all products';

    /**
     * Thực thi lệnh.
     */
    public function handle()
    {
        $this->info('🔄 Bắt đầu cập nhật average_rating và reviews_count...');

        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $product->updateAverageRating();
            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);
        $this->info('✅ Hoàn tất cập nhật!');
    }
}
