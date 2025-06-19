<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Carbon;


class CheckoutController extends Controller
{
    /**
     * Bước 1: Hiển thị form nhập thông tin đặt hàng
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::with(['items.variant.product', 'items.variant.options.attribute'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });


        $orderId = $request->query('orderId');


        $order = null;
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$order) {
            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        return view('client.checkout.index', [
            'cartItems' => $cart->items,
            'totalAmount' => $totalAmount,
            'order' => $order,
        ]);
    }



    /**
     * Bước 2: Lưu thông tin đơn hàng vào DB và chuyển đến trang thanh toán
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate thông tin đơn hàng
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'nullable|string',
            'district' => 'nullable|string',
            'ward' => 'nullable|string',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'shipping_method' => 'required|string',
            'invoice' => 'nullable|in:0,1',
        ]);

        // Kiểm tra giỏ hàng
        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra nếu có đơn hàng pending => chuyển sang luôn trang thanh toán
        $existingOrder = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
        if ($existingOrder) {
            return redirect()->route('checkout.payment', ['orderId' => $existingOrder->id]);
        }

        // Tổng tiền
        $total = $cart->items->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });

        // Gộp địa chỉ
        $shippingAddress = implode(', ', array_filter([
            $request->address,
            $request->ward,
            $request->district,
            $request->province,
        ]));


        // Tạo đơn hàng
        $order = Order::create([
            'user_id'          => $user->id,
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'coupon_id'        => null,
            'order_code'       => strtoupper('OD' . now()->format('YmdHis') . rand(100, 999)),
            'total_amount'     => $total,
            'status'           => 'pending',
            'payment_method'   => 'cod',
            'payment_status'   => 'unpaid',
            'shipping_address' => implode(', ', array_filter([
                $request->address,
                $request->ward,
                $request->district,
                $request->province,
            ])),
            'shipping_method'  => $request->shipping_method,
            'shipping_fee'     => 0,
            'discount_amount'  => 0,
            'note'             => $request->note,

            // THÊM các cột địa chỉ riêng biệt
            'province'         => $request->province,
            'district'         => $request->district,
            'ward'             => $request->ward,
            'address'          => $request->address,
        ]);


        // Lưu các sản phẩm vào bảng order_items
        foreach ($cart->items as $item) {
            $variant = $item->variant;
            if (!$variant || !$variant->product) continue;

            $order->items()->create([
                'product_id'    => $variant->product->id,
                'variant_id'    => $variant->id,
                'price'         => $variant->price,
                'quantity'      => $item->quantity,
                'product_name'  => $variant->product->name,
            ]);
        }

        // Chuyển sang trang thanh toán
        return redirect()->route('checkout.payment', ['orderId' => $order->id]);
    }

 public function payment($orderId)
{
    $user = auth()->user();

    // Lấy đơn hàng kèm theo mã giảm giá (nếu có)
    $order = Order::with([
        'items.variant.product',
        'items.variant.options.attribute',
        'coupon' // Đảm bảo có quan hệ coupon() trong model Order
    ])
    ->where('id', $orderId)
    ->where('user_id', $user->id)
    ->firstOrFail();

    // Lấy lại giỏ hàng (để hiển thị thông tin sản phẩm)
    $cart = Cart::with(['items.variant.product'])
        ->where('user_id', $user->id)
        ->firstOrFail();

    // Tổng tiền hàng
    $cartTotal = $cart->items->sum(function ($item) {
        return $item->quantity * $item->variant->price;
    });

    // Lấy mã giảm giá từ đơn hàng (nếu có)
    $appliedCoupon = $order->coupon ?? null;
    $discountAmount = $order->discount_amount ?? 0;

    // Tổng tiền cuối cùng
    $totalAmount = $cartTotal - $discountAmount;

    // Lấy danh sách mã giảm giá còn hạn
    $availableCoupons = coupon::where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', Carbon::now());
        })
        ->where(function ($query) {
            $query->whereNull('usage_limit')
                ->orWhereColumn('used_count', '<', 'usage_limit');
        })
        ->get();

    return view('client.checkout.payment', compact(
        'user',
        'order',
        'cart',
        'cartTotal',
        'discountAmount',
        'totalAmount',
        'appliedCoupon',
        'availableCoupons'
    ));
}
public function paymentStore(Request $request)
{
    $request->validate([
        'total_amount' => 'required|numeric',
        'shipping_method' => 'required|string',
        'payment_method' => 'required|string',
    ]);

    $user = auth()->user();

    // Lấy đơn hàng đang xử lý
    $order = Order::where('user_id', $user->id)
        ->where('status', 'pending')
        ->latest()
        ->firstOrFail();

    // Cập nhật đơn hàng
    $order->update([
        'total_amount'      => $request->total_amount,
        'shipping_fee'      => $request->shipping_fee ?? 0,
        'discount_amount'   => $request->discount_amount ?? 0,
        'shipping_method'   => $request->shipping_method,
        'payment_method'    => $request->payment_method,
        'payment_status'    => 'unpaid',
        'status'            => 'confirmed',
        'coupon_id'         => $request->coupon_id,
        'note'              => $request->note,
        'confirmed_at'      => now(),
    ]);

    // Cộng lượt dùng mã giảm giá
    if ($request->filled('coupon_id')) {
        $coupon = Coupon::find($request->coupon_id);
        if ($coupon) {
            $coupon->increment('used_count');
        }
    }

    // ✅ Xoá giỏ hàng sau khi đặt hàng
    $cart = \App\Models\Cart::where('user_id', $user->id)->first();
    if ($cart) {
        $cart->items()->delete(); // Xoá sản phẩm
        $cart->delete(); // Xoá luôn giỏ nếu không dùng lại
    }

    return redirect()->route('index')->with('success', 'Đặt hàng thành công!');
}


    /**
     * Hoàn tất thanh toán
     */
    public function completePayment($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Xoá giỏ hàng sau khi thanh toán
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        // Cập nhật trạng thái đơn hàng
        $order->update([
            'payment_status' => 'paid',
            'status'         => 'processing',
        ]);

        return redirect()->route('orders.success')->with('success', 'Đơn hàng của bạn đã được thanh toán!');
    }




public function applyCoupon(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Chưa đăng nhập!'], 401);
    }

    if (auth()->user()->role !== 'customer') {
        return response()->json(['error' => 'Chỉ khách hàng mới được áp dụng mã!'], 403);
    }

    $coupon = Coupon::where('code', $request->code)->first();
    if (!$coupon) {
        return response()->json(['error' => 'Mã giảm giá không tồn tại!'], 404);
    }

    // Lấy giỏ hàng và tổng tạm tính
    $cart = Cart::where('user_id', auth()->id())->with('items.variant.product')->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json(['error' => 'Giỏ hàng của bạn đang trống!'], 400);
    }

    // Tính tổng tiền từ các item (giá * số lượng)
    $originalTotal = $cart->items->sum(function ($item) {
        return $item->variant->price * $item->quantity;
    });

    // Kiểm tra điều kiện áp dụng (ví dụ: đơn tối thiểu)
    if ($originalTotal < $coupon->min_order_amount) {
        return response()->json([
            'error' => 'Đơn hàng phải đạt tối thiểu ' . number_format($coupon->min_order_amount, 0, ',', '.') . '₫ để áp dụng mã này!'
        ], 422);
    }
    if (now()->greaterThan($coupon->expiry_date)) {
        return response()->json(['error' => 'Mã giảm giá đã hết hạn!'], 410);
    }
    if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
        return response()->json(['error' => 'Mã giảm giá đã được sử dụng hết!'], 429);
    }

    // Tính giảm giá và giới hạn theo max_discount
    $discount = ($originalTotal * $coupon->discount_percent / 100);
    $discount = min($discount, $coupon->max_discount);

    return response()->json([
        'code'            => $coupon->code,
        'coupon_id'       => $coupon->id,
        'discount_amount' => $discount,
        'total_amount'    => $originalTotal - $discount,
    ]);
}






}
