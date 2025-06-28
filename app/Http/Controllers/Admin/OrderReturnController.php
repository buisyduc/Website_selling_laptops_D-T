<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReturn;
use App\Models\Order;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderReturnStatusChanged;
class OrderReturnController extends Controller
{
     public function index()
    {
        $returns = OrderReturn::with('order', 'user')->latest()->get();
        return view('admin.returns.index', compact('returns'));
    }

    public function updateStatus(Request $request, $id)
    {
        $return = OrderReturn::findOrFail($id);
        $return->status = $request->status;
        $return->save();

        // Cập nhật đơn hàng nếu cần
        if ($request->status === 'approved') {
            $return->order->return_status = 'approved';
            $return->order->save();
        }

        // Gửi thông báo
        Notification::route('mail', $return->user->email)
            ->notify(new OrderReturnStatusChanged($return));

        return back()->with('success', 'Cập nhật trạng thái hoàn hàng thành công.');
    }
    public function refund($id)
{
    // $return = OrderReturn::findOrFail($id);
     $return = OrderReturn::with('order')->findOrFail($id); // Load luôn order
    $order = $return->order;
    // Chỉ hoàn nếu trạng thái đã được duyệt
    if ($return->status !== 'approved') {
        return back()->with('error', 'Chỉ hoàn tiền khi yêu cầu đã được phê duyệt.');
    }

    // Gọi API hoàn tiền thật ở đây nếu dùng Momo/VNPAY
    $success = $this->mockRefund($return->order->id, $return->order->total);

    if ($success) {
        $return->status = 'refunded';
        $return->save();

        $return->order->return_status = 'refunded';
        $return->order->save();
    }
    

    return back()->with('success', 'Đã hoàn tiền cho khách.');
    // Chỉ hoàn nếu trạng thái đã được duyệt
    if ($return->status !== 'approved') {
        return back()->with('error', 'Chỉ hoàn tiền khi yêu cầu đã được phê duyệt.');
    }
    // Gọi API Momo hoặc VNPAY hoàn tiền ở đây (ví dụ demo)
    $success = $this->mockRefund($order->id, $order->total);

    if ($success) {
        $return->status = 'refunded';
        $return->save();

        $order->return_status = 'refunded';
        $order->save();

        return back()->with('success', 'Đã hoàn tiền thành công.');
    } else {
        return back()->with('error', 'Hoàn tiền thất bại.');
    }
}


private function mockRefund($order_id, $amount)
{
    // Đây là phần demo, gọi API thật ở đây
    return true;
}

}

