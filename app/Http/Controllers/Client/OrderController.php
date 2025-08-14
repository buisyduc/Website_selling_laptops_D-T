<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{


    public function index(Request $request)
    {
        try {
            // Kiểm tra user đã đăng nhập
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
            }

            $query = Order::with([
                'items',
                'items.product',
                'items.variant.options.attribute',
                'items.variant.options.option'
            ])->where('user_id', auth()->id());

            // Lọc theo trạng thái nếu có
            if ($request->has('status') && $request->get('status') !== null) {
                $query->where('status', $request->get('status'));
            }

            // Lấy danh sách đơn hàng mới nhất
            $orders = $query->orderByDesc('created_at')->get();

            // Nếu không có đơn nào thì trả về collection rỗng
            if ($orders === null) {
                $orders = collect();
            }

            return view('client.user.purchase_order', compact('orders'));
        } catch (\Exception $e) {
            // Log lỗi để dễ debug
            Log::error('Lỗi tại OrderController@index: ' . $e->getMessage());

            // Trả về giao diện rỗng với thông báo lỗi
            $orders = collect();
            return view('client.user.purchase_order', compact('orders'))
                ->with('error', 'Có lỗi xảy ra khi tải danh sách đơn hàng.');
        }
    }


   public function show($id)
{
    $order = Order::with([
        'items.variant.options.attribute',
        'items.variant.options.option',
        'items.product'
    ])->where('user_id', Auth::id())->findOrFail($id);

    return view('client.user.purchase.detail', compact('order'));
}


    public function cancel($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }

        $order->update(['status' => 'canceled']);
        return back()->with('success', 'Đã hủy đơn hàng thành công.');
    }

    public function reorder($id)
    {
        $order = Order::with('items')->where('user_id', auth()->id())->findOrFail($id);

        // Xóa giỏ hàng hiện tại và thêm sản phẩm từ đơn hàng cũ
        $cart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cart->items()->delete();

        $addedItems = [];
        $outOfStockItems = [];
        $partiallyAddedItems = [];

        foreach ($order->items as $item) {
            // Kiểm tra variant còn tồn tại và còn hàng không
            $variant = \App\Models\product_variants::find($item->variant_id);

            if (!$variant) {
                $outOfStockItems[] = $item->product_name . ' (sản phẩm không còn tồn tại)';
                continue;
            }

            if ($variant->stock_quantity <= 0) {
                $outOfStockItems[] = $item->product_name . ' (hết hàng)';
                continue;
            }

            // Tính số lượng có thể thêm
            $requestedQuantity = $item->quantity;
            $availableQuantity = $variant->stock_quantity;
            $quantityToAdd = min($requestedQuantity, $availableQuantity);

            if ($quantityToAdd > 0) {
                $cart->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity'   => $quantityToAdd,
                    'price'      => $variant->price, // Sử dụng giá hiện tại
                ]);

                if ($quantityToAdd < $requestedQuantity) {
                    $partiallyAddedItems[] = $item->product_name . " (chỉ thêm được {$quantityToAdd}/{$requestedQuantity} sản phẩm do hạn chế tồn kho)";
                } else {
                    $addedItems[] = $item->product_name;
                }
            }
        }

        // Lưu đầy đủ thông tin khách hàng từ đơn hàng cũ vào session để auto-fill form checkout
        $shippingInfo = [
            'name' => $order->customer_name ?? $order->name,
            'email' => $order->customer_email ?? $order->email ?? auth()->user()->email,
            'phone' => $order->customer_phone ?? $order->phone,
            'address' => $order->customer_address ?? $order->address,
            'province' => $order->province,
            'district' => $order->district,
            'ward' => $order->ward,
            'note' => $order->note,
            'payment_method' => $order->payment_method
        ];

        session(['reorder_shipping_info' => $shippingInfo]);
        session(['is_reorder' => true]);

        // Tạo thông báo dựa trên kết quả
        $messages = [];

        if (!empty($addedItems)) {
            $messages[] = 'Đã thêm thành công: ' . implode(', ', $addedItems);
        }

        if (!empty($partiallyAddedItems)) {
            $messages[] = 'Thêm một phần: ' . implode(', ', $partiallyAddedItems);
        }

        if (!empty($outOfStockItems)) {
            $messages[] = 'Không thể thêm: ' . implode(', ', $outOfStockItems);
        }

        if (empty($addedItems) && empty($partiallyAddedItems)) {
            return redirect()->route('client.orders.index')->with('error', 'Không thể mua lại đơn hàng này vì tất cả sản phẩm đều hết hàng hoặc không còn tồn tại.');
        }

        $successMessage = implode(' | ', $messages) . ' Thông tin giao hàng đã được điền sẵn từ đơn hàng cũ.';

        return redirect()->route('checkout.index')->with('success', $successMessage);
    }
}
