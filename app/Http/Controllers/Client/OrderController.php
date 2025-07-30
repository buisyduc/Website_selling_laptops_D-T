<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{


    public function index(Request $request)
    {
        $query = Order::with('orderItems')->where('user_id', auth()->id());

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $orders = $query->orderByDesc('created_at')->get();

       return view('client.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->where('user_id', Auth::id())->findOrFail($id);
        return view('client.orders.show', compact('order'));
    }
    public function cancel($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Đã hủy đơn hàng thành công.');
    }

    public function reorder($id)
    {
        $order = Order::with('orderItems')->where('user_id', auth()->id())->findOrFail($id);

        $cart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cart->items()->delete();

        foreach ($order->orderItems as $item) {
            $cart->items()->create([
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm lại sản phẩm vào giỏ hàng.');
    }
     public function update(order $order)
    {
        // Khi cập nhật trạng thái thành completed
        if ($request->status === 'completed') {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
        
        return redirect()->back();
    }
}
