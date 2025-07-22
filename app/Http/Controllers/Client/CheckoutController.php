<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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

    $cartTotal = $cart->items->sum(fn($item) => $item->variant->price * $item->quantity);

    $order = null;

    // Ưu tiên lấy từ session
    if (session()->has('current_order_id')) {
        $order = Order::with(['items.variant.product', 'items.variant.options.attribute', 'coupon'])
            ->where('id', session('current_order_id'))
            ->where('user_id', $user->id)
            ->where('status', 'unprocessed')
            ->first();
    }

    // Nếu không có order từ session thì lấy đơn unprocessed mới nhất
    if (!$order) {
        $order = Order::with(['items.variant.product', 'items.variant.options.attribute', 'coupon'])
            ->where('user_id', $user->id)
            ->where('status', 'unprocessed')
            ->latest()
            ->first();
    }

    $appliedCoupon = $order->coupon ?? null;
    $discountAmount = $order->discount_amount ?? 0;
    $totalAmount = $cartTotal - $discountAmount;

    $availableCoupons = Coupon::where(function ($query) {
        $query->whereNull('expires_at')
            ->orWhere('expires_at', '>', now());
    })
        ->where(function ($query) {
            $query->whereNull('usage_limit')
                ->orWhereColumn('used_count', '<', 'usage_limit');
        })
        ->get();

    return view('client.checkout.index', [
        'user' => $user,
        'cartItems' => $cart->items,
        'cartTotal' => $cartTotal,
        'totalAmount' => $totalAmount,
        'order' => $order,
        'appliedCoupon' => $appliedCoupon,
        'discountAmount' => $discountAmount,
        'availableCoupons' => $availableCoupons,
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
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string',

        ]);

        Log::info('Dữ liệu gửi vào request:', $request->all());

        // Lấy giỏ hàng hiện tại
        $cart = Cart::with('items.variant.product')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route(route: 'cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng tiền
        $total = $cart->items->sum(fn($item) => $item->variant->price * $item->quantity);

        // Gộp địa chỉ
        $shippingAddress = implode(', ', array_filter([
            $request->address,
            $request->ward,
            $request->district,
            $request->province,
        ]));

        // 🔍 Tìm đơn hàng `unprocessed` hiện tại (nếu có)
        $order = Order::where('user_id', $user->id)
            ->where('status', 'unprocessed') // Chỉ lấy đơn hàng chưa xử lý
            ->latest()
            ->first();

        if ($order) {
            // ✅ Nếu đã tồn tại, cập nhật lại thông tin
            $order->update([
                'name'             => $request->name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'shipping_address' => $shippingAddress,
                'province'         => $request->province,
                'district'         => $request->district,
                'ward'             => $request->ward,
                'address'          => $request->address,
                'note'             => $request->note,
            ]);

            // Xóa item cũ rồi thêm lại nếu cần (nếu bạn muốn cập nhật theo cart)
            $order->items()->delete();
        } else {
            // ✅ Nếu chưa có thì tạo mới
            $order = Order::create([
                'user_id'          => $user->id,
                'name'             => $request->name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'coupon_id'        => null,
                'order_code'       => strtoupper('OD' . now()->format('YmdHis') . rand(100, 999)),
                'total_amount'     => null, // Tổng tiền sẽ được tính sau khi áp dụng mã giảm giá
                'status'           => 'unprocessed',// Đặt trạng thái ban đầu là 'unprocessed'
                'payment_method'   => null,
                'payment_status'   => 'unpaid',
                'shipping_address' => $shippingAddress,
                'shipping_method'  => 'home_delivery',
                'shipping_fee'     => 0,
                'discount_amount'  => 0,
                'note'             => $request->note,
                'province'         => $request->province,
                'district'         => $request->district,
                'ward'             => $request->ward,
                'address'          => $request->address,
            ]);
        }

        // Dù update hay create đều thêm lại OrderItem từ giỏ hàng
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id'   => $item->variant->product_id,
                'variant_id'   => $item->variant_id,
                'price'        => $item->variant->price,
                'quantity'     => $item->quantity,
                'product_name' => $item->variant->product->name,
            ]);
        }

        // Ghi lại order_id vào session nếu cần
        session(['current_order_id' => $order->id]);

        // Nếu là AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Thông tin đã được lưu thành công!'
            ]);
        }

        return redirect()->back()->with([
            'success' => 'Thông tin đã được lưu thành công!',
            'order_id' => $order->id,
            'switch_to_payment' => true
        ]);
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
    Log::info('Dữ liệu gửi vào request:', $request->all());

    $user = auth()->user();

    $order = Order::where('user_id', $user->id)
        ->where('status', 'unprocessed')
        ->latest()
        ->firstOrFail();

    $cartTotal = $order->items->sum(fn($item) => $item->variant->price * $item->quantity);

    // Tính giảm giá
    $discountAmount = 0;
    $coupon = null;

    if ($request->filled('coupon_id')) {
        $coupon = Coupon::find($request->coupon_id);
        if ($coupon) {
            $discountAmount = round($cartTotal * ($coupon->discount_percent / 100));
            $discountAmount = min($discountAmount, $coupon->max_discount);
        }
    }

    $totalAmount = $cartTotal - $discountAmount;

    // ✅ Xác định trạng thái theo phương thức thanh toán
    $status = match ($request->payment_method) {
        'cod' => 'pending',
        default => 'processing_seller',
    };

    $order->update([
        'total_amount'    => $totalAmount,
        'discount_amount' => $discountAmount,
        'shipping_fee'    => $request->shipping_fee ?? 0,
        'shipping_method' => $request->shipping_method,
        'payment_method'  => $request->payment_method,
        'payment_status'  => $request->payment_method === 'cod' ? 'unpaid' : 'paid',
        'status'          => $status, // 👈 cập nhật trạng thái đúng
        'coupon_id'       => $request->coupon_id,
        'note'            => $request->note,
        'confirmed_at'    => now(),
    ]);

    // Trừ kho
    foreach ($order->items as $item) {
        $variant = $item->variant;
        if ($variant && $variant->stock_quantity !== null) {
            if ($variant->stock_quantity >= $item->quantity) {
                $variant->decrement('stock_quantity', $item->quantity);
            } else {
                return redirect()->route('checkout.payment', ['orderId' => $order->id])
                    ->with('error', 'Sản phẩm "' . $variant->product->name . '" chỉ còn ' . $variant->stock_quantity . ' trong kho.');
            }
        }
    }

    // Cộng lượt dùng mã giảm giá
    if ($request->filled('coupon_id') && $coupon) {
        $coupon->increment('used_count');
    }

    // Xoá giỏ hàng
    if ($cart = Cart::where('user_id', $user->id)->first()) {
        $cart->items()->delete();
        $cart->delete();
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
            $cart->delete();
        }

        foreach ($order->items as $item) {
            $variant = $item->variant;

            if ($variant && $variant->stock_quantity !== null) {
                if ($variant->stock_quantity >= $item->quantity) {
                    $variant->decrement('stock_quantity', $item->quantity);

                    // Load lại từ DB để kiểm tra số lượng mới
                    $updatedVariant = $variant->fresh();

                    dd([
                        'variant_id' => $variant->id,
                        'stock_quantity_sau_khi_tru' => $updatedVariant->stock_quantity,
                        'da_tru_bao_nhieu' => $item->quantity,
                    ]);
                } else {
                    return redirect()->route('checkout.payment', ['orderId' => $order->id])
                        ->with('error', 'Sản phẩm "' . $item->variant->product->name . '" không còn đủ số lượng.');
                }
            }
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
