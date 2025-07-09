<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.variant'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:vnpay,momo,cod',
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->payment_method = $request->payment_method;
        $order->status = $request->status;
        $order->province = $request->province;
        $order->district = $request->district;
        $order->ward = $request->ward;
        $order->address = $request->address;

        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // Nếu cần xóa luôn order items: $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã xóa đơn hàng thành công.');
    }
}
