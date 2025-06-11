<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('client.cart.index', compact('cart'));
    }


    public function add(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        $variant = \App\Models\product_variants::with('product')->find($variantId);

        if (!$variant) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm.');
        }

        $cart = session()->get('cart', []);

        $key = $variantId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$variant->id] = [
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'product_name' => $variant->product->name ?? 'Không tên',
                'color' => 'Không có', // nếu chưa có trường color
                'price' => $variant->price,
                'quantity' => $request->input('quantity', 1),
                'image' => $variant->product->image ?? 'default.jpg', // ✅ Lấy ảnh từ bảng products
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng');
    }


    public function update(Request $request)
    {
        // xử lý cập nhật số lượng
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $variantId = $request->variant_id;

        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }
}
