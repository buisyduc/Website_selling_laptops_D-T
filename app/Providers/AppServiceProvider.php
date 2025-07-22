<?php

namespace App\Providers;

use App\Services\ProductService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
{
    $this->app->bind(ProductService::class, function ($app) {
        return new ProductService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
         View::composer('*', function ($view) {
        $favoriteIds = [];

        if (Auth::check()) {
            $favoriteIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        $view->with('favoriteIds', $favoriteIds);
    });
    }
}
