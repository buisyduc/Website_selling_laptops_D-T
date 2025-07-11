<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    public function vnpay(Order $order)
    {
        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = env('VNP_RETURN_URL');
        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $order->order_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (int)($order->total_amount * 100);
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        ksort($inputData);
        $hashdata = '';
        $query = '';
        foreach ($inputData as $key => $value) {
            $hashdata .= $key . '=' . $value . '&';
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $hashdata = rtrim($hashdata, '&');
        $query = rtrim($query, '&');

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_ResponseCode = $request->vnp_ResponseCode;
        $orderCode = $request->vnp_TxnRef;

        $order = Order::where('order_code', $orderCode)->firstOrFail();

        if ($vnp_ResponseCode == '00') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_transaction_id' => $request->vnp_TransactionNo,
                'payment_response_data' => json_encode($request->all()),
            ]);

            return redirect()->route('checkout.thankYou', $order->id)->with('success', 'Thanh toán thành công!');
        } else {
            $order->update([
                'payment_status' => 'failed',
                'payment_response_data' => json_encode($request->all()),
            ]);

            return redirect()->route('checkout.thankYou', $order->id)->with('error', 'Thanh toán thất bại!');
        }
    }
}
