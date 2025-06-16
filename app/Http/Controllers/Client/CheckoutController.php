<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $cart = $user->cart()->with(['items.variant.product'])->first();

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->variant->price;
        });

        return view('client.checkout.index', [
            'cartItems' => $cart->items,
            'total' => $total
        ]);
    }




    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'payment_method' => 'required',
        ]);

        $user = auth()->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->variant->price;
        });

        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'DT' . strtoupper(uniqid()),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'shipping_address' => $request->shipping_address ?? $request->address,
            'note' => $request->note,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
        ]);

        foreach ($cart->items as $item) {
            order_item::create([
                'order_id' => $order->id,
                'product_id' => $item->variant->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price' => $item->variant->price,
            ]);
        }

        // Xoá giỏ hàng sau khi đặt
        $cart->items()->delete();

        return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công!');
    }
}
