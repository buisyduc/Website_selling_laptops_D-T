<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\OrderCancellationRequested;
use App\Http\Requests\StoreOrderReturnRequest;

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
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);
        return view('client.user.purchase.detail', compact('order'));
    }
    public function cancel($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }
        // Xác định phương thức thanh toán có phải COD không
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

        if (!$isOnline) {
            // Đơn COD + pending: hủy ngay, không cần form
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->stock_quantity += $item->quantity;
                    $item->variant->save();
                }
            }
            $order->update(['status' => 'canceled', 'payment_status' => 'unpaid']);

            // Notify admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancellationRequested($order, 'order_canceled'));
            }

            return redirect()->route('checkout.orderInformation', $order->id)
                ->with('success', 'Đã hủy đơn hàng thành công.');
        }

        // Online: chuyển tới form để cung cấp lý do hủy
        return redirect()->route('orders.return.form', [$order->id, 'action' => 'cancel'])
            ->with('success', 'Vui lòng cung cấp lý do hủy đơn và thông tin bổ sung nếu cần.');
    }
    public function refundPending($id)
    {
        $order = Order::with('items.variant')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }

        $order->update([
            'status' => 'pending',
            'payment_status' => 'refund_pending',
        ]);

        // Thông báo admin: khách hàng yêu cầu hủy yêu cầu trả hàng
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, 'return_cancel_requested'));
        }

        return back()->with('success', 'Shop sẽ sớm xử lý đơn hàng và hoàn tiền về cho bạn.');
    }
    public function huyTraHang($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 'returned') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        // Xác định phương thức thanh toán để quyết định trạng thái thanh toán sau khi hủy trả hàng
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

        // Hoàn tác lại tồn kho do trước đó traHang()/return đã +stock; giờ hủy trả hàng nên -stock tương ứng
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity = max(0, (int)$item->variant->stock_quantity - (int)$item->quantity);
                $item->variant->save();
            }
        }

        // Trả về trạng thái đã giao hàng; thanh toán: COD giữ unpaid, Online giữ paid
        $order->update([
            'status' => 'shipping',
            'payment_status' => $isOnline ? 'paid' : 'unpaid',
        ]);

        // Thông báo admin: khách hàng yêu cầu hủy yêu cầu trả hàng
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, 'return_cancel_requested'));
        }

        return back()->with('success', 'Bạn đã hủy yêu cầu trả hàng thành công.');
    }
    public function refundCanceled($id)
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

        $order->update(['status' => 'pending']);
        $order->update(['payment_status' => 'paid']);

        // Thông báo admin: khách hủy yêu cầu hoàn tiền
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, 'refund_canceled'));
        }

        return back()->with('success', 'Đã hủy yêu cầu hoàn tiền.');
    }
    public function returnRefund($id)
    {
        $order = Order::with('items.variant')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        if ($order->status !== 'shipping') {
            return back()->with('error', 'Không thể yêu cầu trả hàng hoàn tiền.');
        }
    
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save(); // ✅ nhớ lưu lại stock
            }
        }

        $order->update(['status' => 'returned']);

        // Xác định hình thức thanh toán: COD hay online
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

        // Khi khách yêu cầu trả hàng hoàn tiền:
        // Online (VNPay) -> refund_pending; COD/offline -> unpaid
        $order->update([
            'payment_status' => $isOnline ? 'refund_pending' : 'unpaid',
        ]);

        // 🔑 Refresh lại order sau khi update để tránh notify sai dữ liệu
        $order->refresh();

        // Thông báo admin
        $type = $isOnline ? 'return_refund_requested' : 'return_requested';
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, $type));
        }
    
        return back()->with('success', 'Đã yêu cầu trả hàng hoàn tiền.');
    }
    
    public function cancelReturnRefund($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 'returned') {
            return back()->with('error', 'Không thể hủy yêu cầu trả hàng hoàn tiền.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }

        $order->update(['status' => 'shipping']);
        // Xác định hình thức thanh toán để khôi phục đúng trạng thái thanh toán
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);
        // Online: hoàn tiền bị hủy -> về 'paid'. COD: hủy trả hàng -> vẫn 'unpaid'
        $order->update(['payment_status' => $isOnline ? 'paid' : 'unpaid']);

        // Thông báo admin: khách hủy yêu cầu trả hàng hoàn tiền
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, 'return_canceled'));
        }

        return back()->with('success', 'Đã hủy yêu cầu trả hàng hoàn tiền.');
    }

    // Hiển thị form yêu cầu trả hàng / trả hàng hoàn tiền
    public function returnForm($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'shipping', 'completed', 'canceled'])) {
            return back()->with('error', 'Đơn hàng không đủ điều kiện để mở form.');
        }

        // Xác định đơn online (VNPay) để hiển thị trường ngân hàng
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

        $action = request()->query('action'); // ví dụ: cancel
        // Lấy yêu cầu trả hàng/hoàn tiền gần nhất (nếu có) để hiển thị chế độ xem thông tin
        $orderReturn = OrderReturn::where('order_id', $order->id)->latest()->first();
        return view('client.user.returns.create', compact('order', 'isOnline', 'action', 'orderReturn'));
    }

    // Xử lý submit yêu cầu trả hàng / trả hàng hoàn tiền
    public function returnSubmit(StoreOrderReturnRequest $request, $id)
    {
        $order = Order::with('items.variant')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Điều kiện hợp lệ
        $action = $request->input('action'); // cancel hoặc null
        $isShippingOrCompleted = in_array($order->status, ['shipping', 'completed']);
        $isPendingCancelFlow = ($order->status === 'pending' && $action === 'cancel');
        if (!($isShippingOrCompleted || $isPendingCancelFlow)) {
            return back()->with('error', 'Đơn hàng không đủ điều kiện để gửi yêu cầu.');
        }

        $data = $request->validated();

        // Xác định đơn online hay COD (cần sớm để set type)
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
        $isOnline = $method !== '' && !in_array($method, $codKeywords, true);

        // Nếu là luồng hủy đơn (pending + action=cancel) và thanh toán online -> set type = cancel_refund
        if ($isPendingCancelFlow && $isOnline) {
            $data['type'] = 'cancel_refund';
        }

        // Upload ảnh chứng minh (nếu có)
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $imagePaths[] = $file->store('order_returns', 'public');
                }
            }
        }

        // Lưu record yêu cầu trả hàng/hoàn tiền
        $orderReturn = OrderReturn::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => $data['type'] ?? 'return',
            'reason' => $data['reason'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account_name' => $data['bank_account_name'] ?? null,
            'bank_account_number' => $data['bank_account_number'] ?? null,
            'images' => $imagePaths,
            'status' => 'pending',
        ]);

        // Xác định đơn online hay COD (đã có ở trên)

        // Cập nhật trạng thái theo luồng
        if ($isPendingCancelFlow) {
            // Đây là hủy đơn khi đang ở trạng thái pending
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->stock_quantity += $item->quantity;
                    $item->variant->save();
                }
            }
            // Đơn online (VNPay, v.v.): không chuyển sang 'canceled' mà giữ 'pending' và chờ hoàn tiền
            if ($isOnline) {
                $order->update([
                    'status' => 'pending',
                    'payment_status' => 'refund_pending',
                ]);
                $event = 'cancel_refund_requested';
            } else {
                // COD: hủy ngay
                $order->update([
                    'status' => 'canceled',
                    'payment_status' => 'unpaid',
                ]);
                $event = 'order_canceled';
            }
        } else {
            // Trả hàng / Trả hàng hoàn tiền
            if (($data['type'] ?? 'return') === 'return_refund') {
                $order->update([
                    'status' => 'returned',
                    // Online (VNPay) -> refund_pending; COD/offline -> unpaid
                    'payment_status' => $isOnline ? 'refund_pending' : 'unpaid',
                ]);
                $event = 'return_refund_requested';
            } else {
                // Trả hàng (không hoàn tiền)
                $order->update([
                    'status' => 'returned',
                    // COD/offline -> unpaid; Online (hiếm) vẫn pending xác nhận
                    'payment_status' => $isOnline ? 'Waiting_for_order_confirmation' : 'unpaid',
                ]);
                $event = 'return_requested';
            }
        }

        // Thông báo admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, $event));
        }

        return redirect()->route('checkout.orderInformation', $order->id)
            ->with('success', 'Đã gửi yêu cầu thành công. Shop sẽ sớm liên hệ bạn.');
    }

    public function received($id)
    {
        $order = Order::with('items.variant')->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        // Chỉ cho phép khi trạng thái đang giao
        if ($order->status !== 'shipping') {
            return back()->with('error', 'Không thể xác nhận đơn hàng.');
        }
    
        // (Tuỳ hệ thống) Nếu bạn có logic nhập kho khi hoàn trả thì đoạn này hợp lý,
        // còn nếu là "xác nhận đã nhận hàng" thì thường sẽ KHÔNG + stock nữa.
        // -> Mình giữ nguyên code của bạn nhưng lưu ý nên xem lại.
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }
    
        // Cập nhật trạng thái đơn hàng
        $order->update([
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
        
    
        // Gửi thông báo cho tất cả admin: khách đã xác nhận đã nhận hàng
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCancellationRequested($order, 'delivered_confirmed'));
        }
    
        return back()->with('success', 'Đã xác nhận đơn hàng thành công.');
    }
    public function traHang($id)
    {
        $order = Order::with('items.variant')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Chỉ cho phép trả hàng khi đơn đang giao hoặc đã giao
        if (!in_array($order->status, ['shipping', 'delivered'], true)) {
            return back()->with('error', 'Không thể yêu cầu trả hàng.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->stock_quantity += $item->quantity;
                $item->variant->save();
            }
        }

        // Xác định phương thức thanh toán online (ví dụ: VNPay). Mặc định coi là COD/offline nếu không khớp.
        $method = strtolower(trim((string)($order->payment_method ?? '')));
        $onlineMethods = ['vnpay'];
        $isOnline = in_array($method, $onlineMethods, true);

        // Cập nhật trạng thái đơn và trạng thái thanh toán theo phương thức
        $order->update(['status' => 'returned']);
        $order->update([
            'payment_status' => $isOnline ? 'Waiting_for_order_confirmation' : 'unpaid',
        ]);

        return back()->with('success', 'Đã yêu cầu trả hàng.');
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
