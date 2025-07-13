<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ProductReview::class => ProductReviewPolicy::class,
    ];
    

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    //     Gate::define('review-product', function (User $user, Order $order, Product $product) {
    //     return $order->user_id === $user->id
    //         && $order->status === 'completed'
    //         && $order->orderItems->contains('product_id', $product->id)
    //         && !$order->reviews->where('product_id', $product->id)->count();
    // });
    }
    
}
