<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController as ClientHomeController;
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



Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('admin/index', [HomeController::class, 'index'])->name('admin.index');
    //categories
    Route::get('admin/categories',[CategorieController::class,'index'])->name('categories');
    Route::post('admin/categories/store', [CategorieController::class, 'store'])->name('categories.store');
    Route::delete('admin/categories/delete/{category}',[CategorieController::class, 'destroy'])->name('categories.destroy');

    //sub-categories
    Route::get('admin/sub-categories',[CategorieController::class,'sub_categories'])->name('sub-categories');
    //brands
    Route::get('admin/brands',[BrandController::class,'index'])->name('brands');
    Route::post('admin/brands/store', [BrandController::class, 'store'])->name('brands.store');

    //product-list
    Route::get('admin/product-list',[ProductController::class,'index'])->name('product-list');
    //product-create
    Route::get('admin/product-create',[ProductController::class,'create'])->name('product.create');
    Route::post('admin/product-store', [ProductController::class, 'store'])->name('product.store');
    //image
    Route::post('admin/product/images/store', [ProductController::class, 'store'])->name('product.images.store');
    //

});
