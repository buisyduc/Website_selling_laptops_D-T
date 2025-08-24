<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderNotification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;


class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Ánh xạ trạng thái tiếng Việt sang status code trong DB
        $statusMap = [
            'pending' => ['bg-warning', 'Chờ xác nhận'],
            'processing_seller' => ['bg-primary', 'Đã xác nhận'],
            'processing' => ['bg-info', 'Đang giao hàng'],
            'shipping' => ['bg-secondary', 'Đã giao hàng'],
            'completed' => ['bg-success', 'Hoàn thành'],
            'cancelled' => ['bg-danger', 'Đã hủy'],
            'canceled' => ['bg-danger', 'Đã hủy'],
            'returned' => ['bg-secondary', 'Trả hàng/Hoàn tiền'],

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
        return view('admin.Orders.show', compact('order'));
    }

    // Hiển thị thông tin yêu cầu trả hàng/hoàn tiền gần nhất từ bảng order_returns
    public function returnInfo(Order $order)
    {
        $order->load(['user']);
        $orderReturn = \App\Models\OrderReturn::where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$orderReturn) {
            return back()->with('error', 'Chưa có yêu cầu trả hàng/hoàn tiền cho đơn này.');
        }

        return view('admin.Orders.return_show', compact('order', 'orderReturn'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->status === 'completed') {
            return back()->with('error', '❌ Đơn hàng đã hoàn thành, không thể thay đổi trạng thái.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing_seller,confirmed,shipping,processing,completed,cancelled,canceled,returned',
        ]);

        // Cập nhật trạng thái đơn hàng
        $order->status = $request->status;

        // 👉 Nếu là VNPAY/MOMO và trạng thái = "processing_seller" (đã xác nhận) => coi như đã thanh toán
        if ($request->status === 'processing_seller' && in_array($order->payment_method, ['vnpay', 'momo'])) {
            $order->payment_status = 'paid';
        }

        // 👉 Nếu hủy đơn hàng mà đã thanh toán online thì chuyển payment_status -> refunded
        if (in_array($request->status, ['cancelled', 'canceled', 'returned'])) {
            if (in_array($order->payment_method, ['vnpay', 'momo']) && $order->payment_status === 'paid') {
                $order->payment_status = 'refunded';
            }
        }

        // 👉 Nếu đơn COD hoàn thành thì coi là đã thanh toán
        if ($request->status === 'completed' && $order->payment_method === 'cod') {
            $order->payment_status = 'paid';
        }

        $order->save();
        Mail::to($order->user->email)->send(new OrderMail($order, 'status'));

        return back()->with('success', '✅ Cập nhật trạng thái thành công.');
    }
    public function approveRequest($orderId)
    {
        $order = order::findOrFail($orderId);

        if ($order->customer_request_status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        if ($order->customer_request === 'cancel') {
            $order->status = 'cancelled';
        } elseif ($order->customer_request === 'return') {
            $order->status = 'refund';
        }

        $order->update([
            'customer_request_status' => 'approved',
            'customer_request_processed_at' => now(),
        ]);

        return back()->with('success', 'Yêu cầu đã được duyệt.');
    }

    public function rejectRequest($orderId)
    {
        $order = order::findOrFail($orderId);

        if ($order->customer_request_status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        $order->update([
            'customer_request_status' => 'rejected',
            'customer_request_processed_at' => now(),
        ]);

        return back()->with('success', 'Yêu cầu đã bị từ chối.');
    }

    /**
     * Duyệt yêu cầu trả hàng/hoàn tiền từ bảng order_returns
     */
    public function approveReturn(Order $order)
    {
        // Lấy yêu cầu trả hàng/hoàn tiền mới nhất
        $orderReturn = OrderReturn::where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$orderReturn) {
            return back()->with('error', 'Không tìm thấy yêu cầu trả hàng/hoàn tiền.');
        }

        if ($orderReturn->status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        // Cập nhật trạng thái yêu cầu
        $orderReturn->status = 'approved';
        $orderReturn->save();

        // Cập nhật đơn hàng: giữ trạng thái returned
        $order->status = 'returned';

        // Nếu đơn thanh toán COD -> cập nhật payment_status thành 'returned'
        if (strtolower((string) $order->payment_method) === 'cod') {
            $order->payment_status = 'returned';
        }

        $order->save();

        return back()->with('success', 'Đã duyệt yêu cầu trả hàng/hoàn tiền.');
    }

    /**
     * Từ chối yêu cầu trả hàng/hoàn tiền từ bảng order_returns
     */
    public function rejectReturn(Order $order)
    {
        $orderReturn = OrderReturn::where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$orderReturn) {
            return back()->with('error', 'Không tìm thấy yêu cầu trả hàng/hoàn tiền.');
        }

        if ($orderReturn->status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        $orderReturn->status = 'rejected';
        $orderReturn->save();

        return back()->with('success', 'Đã từ chối yêu cầu trả hàng/hoàn tiền.');
    }
}
