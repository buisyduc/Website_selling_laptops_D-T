<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReturnOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->where('status', 'delivered')->get();
        return view('client.returns.index', compact('orders'));
    }

    public function create($order_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        return view('client.returns.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:255',
            'note' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('return_images', 'public');
        }

        OrderReturn::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'reason' => $request->reason,
            'note' => $request->note,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('returns.index')->with('success', 'Gửi yêu cầu hoàn hàng thành công.');
    }
    public function history()
{
    $returns = OrderReturn::where('user_id', Auth::id())->latest()->get();
    return view('client.returns.history', compact('returns'));
}
}
