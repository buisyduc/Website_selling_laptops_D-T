<?php

use App\Http\Controllers\Admin\AttributesProduct;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController as ClientHomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ProductCommentController;
use App\Http\Controllers\Client\ProductReviewController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

   
Route::get('/', [ClientHomeController::class, 'index'])->name('index');
Route::get('/home', [ClientHomeController::class, 'home'])->name('home');
Route::get('/login', function () {
    return response()->json(['message' => 'Vui lòng đăng nhập để tiếp tục.'], 401);
})->name('login');

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('signup',[AuthController::class,'signup'])->name( 'signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/account-management', [AuthController::class, 'management'])->name('management');


//giao diện chung
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');

//bình luận
Route::post('/products/{product}/comments', [ProductCommentController::class, 'store'])->name('comments.store'); 

//search
Route::get('/products/search', [ProductController::class, 'searcPr'])->name('client.products.searchPr');



// // Product routes
// Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');
// Route::get('/api/products/{id}/variants', [ProductController::class, 'getVariants'])->name('products.variants');


Route::middleware(['auth', 'is_customer'])->group(function () {
    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update-item', [CartController::class, 'updateItem'])->name('cart.updateItem');
    Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear-ajax', [CartController::class, 'clearAjax'])->name('cart.clearAjax');
    Route::put('/cart', [CartController::class, 'updateAll'])->name('cart.updateAll');
    require __DIR__ . '/cart.php';
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');
    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->middleware(['auth', 'is_customer', 'ensure.cart.not.empty'])
        ->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{orderId}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{orderId}/complete', [CheckoutController::class, 'completePayment'])->name('checkout.complete');
    Route::post('/checkout/payment/store', [CheckoutController::class, 'paymentStore'])->name('checkout.paymentStore');
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');

    // Orders - Đơn hàng
    Route::get('/orders', [OrderController::class, 'index'])->name('client.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('client.orders.show');
    Route::get('/order/thank-you/{orderId}', [CheckoutController::class, 'thankYou'])->name('checkout.thankYou');
    Route::delete('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    //rate
    // Route cho create và store với order/product
    Route::middleware(['auth'])->prefix('orders/{order}/products/{product}')->group(function () {
        Route::controller(ProductReviewController::class)->group(function () {
            Route::get('/review', 'create')->name('reviews.create');
            Route::post('/review', 'store')->name('reviews.store');
        });
    });

    // Route cho edit, update, destroy với review ID
    Route::middleware(['auth'])->group(function () {
        Route::resource('reviews', ProductReviewController::class)
            ->only(['edit', 'update', 'destroy'])
            ->parameters(['reviews' => 'review']) // Thêm dòng này nếu bạn muốn thống nhất parameter name
            ->names([
                'edit' => 'reviews.edit',
                'update' => 'reviews.update',
                'destroy' => 'reviews.destroy'
            ]);
    });

    // API routes
    Route::get('/api/products/{product}/reviews', 
        [ProductReviewController::class, 'index'])
        ->name('api.products.reviews');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('admin/index', [HomeController::class, 'index'])->name('admin.index');
//categories
    Route::get('admin/categories',[CategorieController::class,'index'])->name('categories');
    Route::post('admin/categories/store', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('categories/trashed', [CategorieController::class, 'trashed'])->name('categories.trashed');
    Route::delete('categories/{category}', [CategorieController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/{id}/restore', [CategorieController::class, 'restore'])->name('categories.restore');
    Route::post('admin/categories/restore-all', [CategorieController::class, 'restoreAll'])->name('categories.restoreAll');
    Route::delete('categories/{id}/force-delete', [CategorieController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::delete('admin/categories/force-delete-all', [CategorieController::class, 'forceDeleteAll'])->name('categories.forceDeleteAll');
    Route::get('admin/categories/{category}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    Route::put('admin/categories/{category}', [CategorieController::class, 'update'])->name('categories.update');
    Route::get('admin/sub-categories',[CategorieController::class,'sub_categories'])->name('sub-categories');
//brands
    Route::get('admin/brands',[BrandController::class,'index'])->name('brands');
    Route::post('admin/brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::post('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::post('admin/brands/restore-all', [BrandController::class, 'restoreAll'])->name('brands.restoreAll');
    Route::delete('admin/brands/force-delete-all', [BrandController::class, 'forceDeleteAll'])->name('brands.forceDeleteAll');
    Route::get('brands/trashed', [BrandController::class, 'trashed'])->name('brands.trashed');
    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.forceDelete');
    Route::get('admin/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('admin/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');

//product-list
    Route::get('admin/product-list',[ProductController::class,'index'])->name('product-list');
    Route::get('product/trashed', [ProductController::class, 'trashed'])->name('product.trashed');
    Route::get('admin/product-create',[ProductController::class,'create'])->name('product.create');
    Route::post('admin/product-store', [ProductController::class, 'store'])->name('product.store');
    Route::post('admin/product/images/store', [ProductController::class, 'store'])->name('product.images.store');
    Route::post('/debug-variants', [ProductController::class, 'debugVariantsStructure'])->name('debug.variants');
    Route::delete('product/delete/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('admin/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/{id}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::delete('product/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('product.forceDelete');
    Route::post('admin/product/restore-all', [ProductController::class, 'restoreAll'])->name('product.restoreAll');
    Route::delete('admin/product/force-delete-all', [ProductController::class, 'forceDeleteAll'])->name('product.forceDeleteAll');
    Route::put('admin/product/{brand}', [ProductController::class, 'update'])->name('product.update');
    Route::get('admin/product-view/{id}',[ProductController::class,'view'])->name('product.view');
//attributes
    Route::get('admin/attributes',[AttributesProduct::class,'index'])->name('attributes');
    Route::post('admin/attributes/store', [AttributesProduct::class, 'store'])->name('attributes.store');
    //order
    Route::get('admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('admin/orders/{id}/edit', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('admin/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::patch('comments/toggle/{comment}', [CommentController::class, 'toggleVisibility'])->name('comments.toggle');
        Route::get('comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
        
    });
    // Rate
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Resource route cho reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

});
