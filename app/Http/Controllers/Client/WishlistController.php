<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
{
    $products = Product::latest()->get(); // hoặc phân trang

    $favoriteIds = [];

    if (auth()->check()) {
        $favoriteIds = Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->toArray();
    }

    return view('client.index', compact('products', 'favoriteIds'));
}

public function store(Request $request, Product $product)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập để sử dụng chức năng này!',
        ], 401);
    }

    $wishlist = Wishlist::firstOrNew([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    if ($wishlist->exists) {
        $wishlist->delete();

        return response()->json([
            'success' => true,
            'status' => 'removed',
            'message' => '💔 Đã xoá khỏi danh sách yêu thích!',
        ]);
    }

    $wishlist->save();

    return response()->json([
        'success' => true,
        'status' => 'added',
        'message' => '💖 Đã thêm vào danh sách yêu thích!',
    ]);
}

}
