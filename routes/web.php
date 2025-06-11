<?php

use App\Http\Controllers\Admin\AttributesProduct;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController as ClientHomeController;
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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('signup',[AuthController::class,'signup'])->name( 'signup');

//client
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/{id}', [ClientProductController::class, 'showById'])->name('client.products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantityAjax'])->name('cart.updateQuantity');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');


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
    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.forceDelete');
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






});
