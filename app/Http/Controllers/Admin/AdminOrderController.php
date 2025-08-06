<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderNotification;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Ánh xạ trạng thái tiếng Việt sang status code trong DB
        $statusMap = [
            'chờ thanh toán' => 'pending',
            'chờ lấy hàng' => 'processing_seller',
            'Chờ giao hàng' => 'processing',
            'vận chuyển' => 'shipping',
            'hoàn thành' => 'completed',
            'đã hủy' => 'cancelled',
            'đã hủy' => 'canceled',
            'trả hàng/hoàn tiền' => 'returned',

        ];

        if ($request->filled('search')) {
            $search = trim($request->search);
            $normalized = mb_strtolower($search);

            $query->where(function ($q) use ($search, $normalized, $statusMap) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");

                // Nếu search giống trạng thái
                if (array_key_exists($normalized, $statusMap)) {
                    $q->orWhere('status', $statusMap[$normalized]);
                }

                // Nếu là số, tìm theo total_amount gần đúng
                if (is_numeric($search)) {
                    $q->orWhere('total_amount', '>=', $search - 10000) // cho sai số ±10k
                        ->where('total_amount', '<=', $search + 10000);
                }
            });
        }

        $orders = $query->latest()->paginate(20);

        // Thống kê
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $processingOrders = Order::whereIn('status', ['pending', 'processing', 'processing_seller'])->count();
        $monthlyRevenue = Order::where('status', 'completed')->sum('total_amount');

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'completedOrders',
            'processingOrders',
            'monthlyRevenue'
        ));
    }




    public function show(Order $order)
    {
        $order->load(['user', 'items.variant.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->status === 'completed') {
            return back()->with('error', '❌ Đơn hàng đã hoàn thành, không thể thay đổi trạng thái.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing_seller,confirmed,shipping,processing,completed,cancelled,canceled,returned',
        ]);

        // Cập nhật trạng thái
        $order->status = $request->status;
        $order->save();

        return back()->with('success', '✅ Cập nhật trạng thái thành công.');
    }

    public function sendEmail(Request $request, Order $order)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to($order->email)->send(new OrderNotification($request->subject, $request->message));
            return back()->with('success', '✅ Email đã được gửi cho khách hàng.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gửi email thất bại: ' . $e->getMessage());
        }
    }
}
