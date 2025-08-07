<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\Order;

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

        $order = Order::where('order_code', $vnp_TxnRef)->firstOrFail();

        if ($vnp_ResponseCode == '00') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
            return redirect()->route('checkout.thankYou', $order->id)->with('success', 'Thanh toán VNPay thành công!');
        } else {
            return redirect()->route('checkout.index', $order->id)->with('error', 'Thanh toán thất bại hoặc bị huỷ.');
        }
    }
}
