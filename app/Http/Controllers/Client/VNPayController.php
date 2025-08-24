<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\OrderPlaced;

class VNPayController extends Controller
{
    public function redirectToVNPay($orderId)
    {
        $order = Order::findOrFail($orderId);

        $vnp_Url = config('vnpay.vnp_url');
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');

        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);

        $hashdataArr = [];
        $query = '';
        foreach ($inputData as $key => $value) {
            $hashdataArr[] = urlencode($key) . '=' . urlencode($value);
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $hashdata = implode('&', $hashdataArr);
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        $vnp_Url .= '?' . $query . 'vnp_SecureHash=' . $vnp_SecureHash;

        return redirect($vnp_Url);
    }


    public function handleReturn(Request $request)
    {
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');

        try {
            DB::transaction(function () use ($vnp_ResponseCode, $vnp_TxnRef) {
                $order = Order::with(['items.variant', 'coupon'])->where('order_code', $vnp_TxnRef)->firstOrFail();
                $user = auth()->user();

                if ($vnp_ResponseCode == '00') {
                    // Trừ kho
                    foreach ($order->items as $item) {
                        $variant = $item->variant;
                        if ($variant && $variant->stock_quantity !== null) {
                            $variant->decrement('stock_quantity', $item->quantity);
                        }
                    }

                    // Cộng lượt dùng mã giảm giá
                    if ($order->coupon) {
                        $order->coupon->increment('used_count');
                    }

                    // Xoá giỏ hàng
                    if ($user && ($cart = \App\Models\Cart::where('user_id', $user->id)->first())) {
                        $cart->items()->delete();
                        $cart->delete();
                    }

                    // Cập nhật trạng thái đơn hàng
                    $order->update([
                        'payment_status' => 'pending',
                        'status'         => 'pending',
                        'payment_method' => 'vnpay', // Thêm dòng này!
                        'paid_at'        => now(),
                    ]);

                    // Thông báo admin có đơn hàng mới (VNPay)
                    try {
                        $admins = User::where('role', 'admin')->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new OrderPlaced($order));
                        }
                    } catch (\Throwable $e) {
                        // Không chặn flow nếu thông báo lỗi
                    }
                } else {
                    // Nếu thanh toán thất bại từ VNPay, cập nhật trạng thái thất bại
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'failed',
                    ]);
                    throw new \Exception('Thanh toán VNPay thất bại hoặc bị huỷ.');
                }
            });

            $order = Order::where('order_code', $vnp_TxnRef)->first();
            return redirect()->route('checkout.orderInformation', $order->id)->with('success', 'Thanh toán VNPay thành công!');
        } catch (\Exception $e) {
            // Cập nhật trạng thái thất bại nếu chưa cập nhật ở trên (phòng trường hợp lỗi bất ngờ)
            $order = Order::where('order_code', $vnp_TxnRef)->first();
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'failed',
                ]);
            }
            return redirect()->route('checkout.orderInformation', $order->id ?? null)
                ->with('error', 'Thanh toán VNPay thất bại hoặc bị huỷ. Bạn có thể thanh toán lại đơn hàng!');
        }
    }
}
