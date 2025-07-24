<?php

return [
    'vnp_url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'tmn_code' => env('VNP_TMN_CODE'),
    'hash_secret' => env('VNP_HASH_SECRET'),
    'return_url' => env('VNP_RETURNURL'),
];
