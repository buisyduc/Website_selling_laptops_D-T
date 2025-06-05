<?php

use App\Http\Controllers\Admin\AttributesProduct;
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
// 1. Hiển thị danh sách các danh mục đã bị xóa mềm (trashed)
    Route::get('categories/trashed', [CategorieController::class, 'trashed'])->name('categories.trashed');
// 2. Xóa mềm một danh mục (soft delete)
   Route::delete('categories/{category}', [CategorieController::class, 'destroy'])->name('categories.destroy');
    // 3. Khôi phục một danh mục đã bị xóa mềm
    Route::post('categories/{id}/restore', [CategorieController::class, 'restore'])->name('categories.restore');
    Route::post('admin/categories/restore-all', [CategorieController::class, 'restoreAll'])->name('categories.restoreAll');

    // 4. Xóa vĩnh viễn một danh mục đã bị xóa mềm (force delete)
    Route::delete('categories/{id}/force-delete', [CategorieController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::delete('admin/categories/force-delete-all', [CategorieController::class, 'forceDeleteAll'])->name('categories.forceDeleteAll');
    //hiển thị blade sửa
    Route::get('admin/categories/{category}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    //sửatrashed
    Route::put('admin/categories/{category}', [CategorieController::class, 'update'])->name('categories.update');
    //sub-categories
    Route::get('admin/sub-categories',[CategorieController::class,'sub_categories'])->name('sub-categories');

//brands
    Route::get('admin/brands',[BrandController::class,'index'])->name('brands');
    Route::post('admin/brands/store', [BrandController::class, 'store'])->name('brands.store');
//xoa mem thuong hieu
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::post('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::post('admin/brands/restore-all', [BrandController::class, 'restoreAll'])->name('brands.restoreAll');
    //xóa vinh viễn
    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.forceDelete');
    Route::delete('admin/brands/force-delete-all', [BrandController::class, 'forceDeleteAll'])->name('brands.forceDeleteAll');

//hiển thị danh sách xóa mềm
    Route::get('brands/trashed', [BrandController::class, 'trashed'])->name('brands.trashed');

    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.forceDelete');
       //hiển thị blade sửa
    Route::get('admin/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    //sửatrashed
    Route::put('admin/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    //product-list
    Route::get('admin/product-list',[ProductController::class,'index'])->name('product-list');
    //product-create
    Route::get('admin/product-create',[ProductController::class,'create'])->name('product.create');
    Route::post('admin/product-store', [ProductController::class, 'store'])->name('product.store');
    //image
    Route::post('admin/product/images/store', [ProductController::class, 'store'])->name('product.images.store');
     //
    Route::post('/debug-variants', [ProductController::class, 'debugVariantsStructure'])->name('debug.variants');
    //attributes
    Route::get('admin/attributes',[AttributesProduct::class,'index'])->name('attributes');
    Route::post('admin/attributes/store', [AttributesProduct::class, 'store'])->name('attributes.store');






});
