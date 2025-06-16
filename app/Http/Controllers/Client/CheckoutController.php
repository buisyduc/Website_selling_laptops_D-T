<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function showForm()
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
    }
    return view('client.checkout.form', compact('cart'));
}
   public function process(Request $request)
{
    
    $request->validate([
        'fullname' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email',
        'address' => 'required|string',
        'payment_method' => 'required|in:cod,vnpay',
    ]);

    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Giỏ hàng rỗng.');
    }

    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    // Tạo mã đơn hàng ngẫu nhiên
    $orderCode = strtoupper(Str::random(10));

    // Lưu đơn hàng vào CSDL
    $order = order::create([
        'user_id' => auth()->id(),
        'order_code' => $orderCode,
        'total_amount' => $total,
        'status' => 'pending',
        'payment_method' => $request->payment_method,
        'payment_status' => $request->payment_method === 'cod' ? 'unpaid' : 'pending',
        'shipping_address' => $request->address,
        'note' => null,
        'coupon_id' =>null,

    ]);
    foreach ($cart as $productId =>$item){
        order_item::create([
            'order_id' => $order->id,
            'product_id' => $productId,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    session()->forget('cart');
     return redirect()->route('checkout.success')->with('message', 'Đặt hàng thành công!');
    
}
}
