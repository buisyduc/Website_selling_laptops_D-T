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

    // Tìm theo mã, tên, điện thoại
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('order_code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // Ánh xạ trạng thái hiển thị sang key lưu trong db
    $statusText = trim($request->input('status_text', ''));

    $map = [
        'Chờ thanh toán' => 'pending',
        'pending' => 'pending',
        'Chờ lấy hàng' => 'processing_seller',
        'processing_seller' => 'processing_seller',
        'Vận chuyển' => 'shipping',
        'shipping' => 'shipping',
        'Chờ giao hàng' => 'processing',
        'processing' => 'processing',
        'Hoàn thành' => 'completed',
        'completed' => 'completed',
        'Đã hủy' => 'cancelled',
        'Đã hủy' => 'canceled',
        'Trả hàng/Hoàn tiền' => 'returned',
        'returned' => 'returned',
    ];

    if ($statusText !== '') {
        // Chuẩn hóa: không phân biệt hoa thường, lược bỏ dấu
        $normalized = mb_strtolower($statusText);
        // đơn giản dò từng mapping bằng so sánh không phân biệt hoa
        $matchedStatus = null;
        foreach ($map as $label => $key) {
            if (mb_strtolower($label) === $normalized) {
                $matchedStatus = $key;
                break;
            }
        }
        if ($matchedStatus) {
            $query->where('status', $matchedStatus);
        }
    }

   $orders = $query->latest()->paginate(20);

    // Thống kê
    $totalOrders = Order::count();
    $completedOrders = Order::where('status', 'completed')->count();
    $processingOrders = Order::whereIn('status', ['pending', 'processing', 'processing_seller'])->count();
    $monthlyRevenue = Order::where('status', 'completed')
        ->sum('total_amount');

    return view('admin.orders.index', compact(
        'orders', 'totalOrders', 'completedOrders', 'processingOrders', 'monthlyRevenue'
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
