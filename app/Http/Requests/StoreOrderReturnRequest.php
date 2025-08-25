<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class StoreOrderReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:return,return_refund,cancel_refund',
            'reason' => 'required|string|min:10',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'images' => 'nullable|array|max:5',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $orderId = $this->route('id');
            $order = Order::where('id', $orderId)->where('user_id', auth()->id())->first();
            if (!$order) {
                $validator->errors()->add('order', 'Đơn hàng không hợp lệ.');
                return;
            }

            $method = strtolower((string)($order->payment_method ?? ''));
            $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
            $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

            $needsBank = in_array($this->input('type'), ['return_refund','cancel_refund'], true);
            if ($needsBank && $isOnline) {
                if (!$this->filled('bank_name')) {
                    $validator->errors()->add('bank_name', 'Vui lòng nhập tên ngân hàng.');
                }
                if (!$this->filled('bank_account_name')) {
                    $validator->errors()->add('bank_account_name', 'Vui lòng nhập tên chủ tài khoản.');
                }
                if (!$this->filled('bank_account_number')) {
                    $validator->errors()->add('bank_account_number', 'Vui lòng nhập số tài khoản.');
                }
            }
        });
    }
}
