<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\product_variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;




class CartController extends Controller
{
public function index()
{
    $userId = auth()->id();
    $cart = Cart::with([
                'items.variant.product.images',
                'items.variant.options.attribute',
                'items.variant.options.option'
            ])
            ->where('user_id', $userId)
            ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return view('client.cart.index', [
            'cartItems' => collect(),
            'total'     => 0
        ]);
    }

    $total = $cart->items->sum(function ($item) {
        return $item->quantity * $item->variant->price;
    });

    return view('client.cart.index', [
        'cartItems' => $cart->items,
        'total'     => $total
    ]);
}


public function add(Request $request)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập.'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = product_variants::find($request->variant_id);
        if (!$variant) {
            return response()->json(['status' => 'error', 'message' => 'Biến thể không tồn tại']);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = $cart->items()->where('variant_id', $variant->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'variant_id' => $variant->id,
                'quantity' => $request->quantity,
            ]);
        }

        // Load lại items kèm variant
        $cart->load('items.variant');

        $totalQuantity = $cart->items()->sum('quantity');
        $totalAmount = 0;
        foreach ($cart->items as $item) {
            if ($item->variant) {
                $totalAmount += $item->quantity * $item->variant->price;
            }
        }

        return response()->json([
            'status' => 'success',
            'total_quantity' => $totalQuantity,
            'total_amount' => number_format($totalAmount, 0, ',', '.') . '₫'
        ]);

    } catch (\Exception $e) {
        // Ghi log lỗi để dễ debug
        Log::error('Lỗi khi thêm vào giỏ hàng: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi server: ' . $e->getMessage()
        ], 500);
    }
}

    public function updateItem(Request $request)
{
    $user = auth()->user();
    $cart = $user->cart;
    $variantId = $request->input('variant_id');
    $quantity = max((int) $request->input('quantity'), 1);

    $item = $cart->items()->where('variant_id', $variantId)->first();
    if (!$item) {
        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ']);
    }

    $item->quantity = $quantity;
    $item->save();

    // Tính lại tổng từng dòng
    $itemTotal = $item->quantity * $item->variant->price;

    // Tính lại tổng giỏ
    $grandTotal = $cart->items()->with('variant')->get()->sum(function ($i) {
        return $i->quantity * $i->variant->price;
    });

    return response()->json([
        'success' => true,
        'item_total' => number_format($itemTotal, 0, ',', '.') . '₫',
        'cart_total' => number_format($grandTotal, 0, ',', '.') . '₫',
    ]);
}


    // Xoá 1 sản phẩm khỏi giỏ (AJAX)
    public function remove($variantId)
    {
        $user = auth()->user();
        $cart = $user->cart;
        if (!$cart) return response()->json(['success' => false]);

        $item = $cart->items()->where('variant_id', $variantId)->first();
        if ($item) $item->delete();

        $cartTotal = $cart->items->sum(fn($i) => $i->quantity * ($i->variant->price ?? 0));
        $cartEmpty = $cart->items()->count() === 0;

        return response()->json([
            'success' => true,
            'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫',
            'cart_empty' => $cartEmpty,
        ]);
    }



public function clear()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
    }

    $cart = $user->cart;

    if ($cart) {
        $cart->items()->delete(); // Xoá hết item trong giỏ hàng
    }

    return redirect()->route('cart.index')->with('success', 'Đã xoá toàn bộ giỏ hàng.');
}


public function clearAjax()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập.'
        ], 401);
    }

    $cart = $user->cart;

    if ($cart) {
        $cart->items()->delete(); // Xoá tất cả sản phẩm trong giỏ
    }

    return response()->json([
        'success' => true,
        'message' => 'Đã xoá toàn bộ giỏ hàng.'
    ]);
}

public function updateAll(Request $request)
{
    $user = auth()->user();
    $cart = $user->cart;
    if (! $cart) {
        return redirect()->back()->with('error', 'Giỏ hàng trống.');
    }

    $quantities = $request->input('quantities', []);
    foreach ($quantities as $variantId => $qty) {
        $item = $cart->items()->where('variant_id', $variantId)->first();
        if ($item && $qty > 0) {
            $item->update(['quantity' => (int) $qty]);
        }
    }

    return redirect()->route('cart.index')
                     ->with('success', 'Cập nhật giỏ hàng thành công.');
}
// App\Http\Controllers\Client\CartController.php

public function summary()
{
    $user = auth()->user();

    if (!$user || !$user->cart) {
        return response()->json([
            'success' => true,
            'total_quantity' => 0,
            'total_amount' => '0₫'
        ]);
    }

    $cart = $user->cart;

    $totalQty = $cart->items()->sum('quantity');

    // Eager load để tránh N+1
    $items = $cart->items()->with('variant')->get();

    $totalAmount = $items->sum(function ($item) {
        return $item->variant ? $item->quantity * $item->variant->price : 0;
    });

    return response()->json([
        'success' => true,
        'total_quantity' => $totalQty,
        'total_amount' => number_format($totalAmount, 0, ',', '.') . '₫'
    ]);
}


}
