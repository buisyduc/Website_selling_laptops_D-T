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
        
        // Kiểm tra tồn kho
        $currentQuantityInCart = $cartItem ? $cartItem->quantity : 0;
        $newTotalQuantity = $currentQuantityInCart + $request->quantity;
        
        if ($newTotalQuantity > $variant->stock_quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể thêm sản phẩm. Chỉ còn ' . $variant->stock_quantity . ' sản phẩm trong kho, bạn đã có ' . $currentQuantityInCart . ' trong giỏ hàng.'
            ]);
        }

        if ($cartItem) {
            $cartItem->quantity = $newTotalQuantity;
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

    $variant = $item->variant;

    if ($quantity > $variant->stock_quantity) {
        // Cập nhật quantity về số lượng tối đa có thể
        $item->quantity = $variant->stock_quantity;
        $item->save();
        
        // Tính lại tổng giỏ hàng sau khi cập nhật
        $cart->load('items.variant');
        $totalQuantity = $cart->items()->sum('quantity');
        $totalAmount = $cart->items->sum(function ($i) {
            return $i->variant ? $i->quantity * $i->variant->price : 0;
        });
        
        // Tính item total sau khi cập nhật
        $itemTotal = $item->quantity * $variant->price;
        
        return response()->json([
            'success' => false,
            'message' => 'Chỉ còn ' . $variant->stock_quantity . ' sản phẩm trong kho.',
            'allowed_quantity' => $variant->stock_quantity,
            'item_total' => number_format($itemTotal, 0, ',', '.') . '₫',
            'cart_total' => number_format($totalAmount, 0, ',', '.') . '₫',
            'total_quantity' => $totalQuantity,
            'total_amount' => number_format($totalAmount, 0, ',', '.') . '₫'
        ]);
    }

    // ✅ Cập nhật nếu hợp lệ
    $item->quantity = $quantity;
    $item->save();

    $itemTotal = $item->quantity * $variant->price;

    $grandTotal = $cart->items()->with('variant')->get()->sum(function ($i) {
        return $i->quantity * $i->variant->price;
    });
    
    // Tính tổng số lượng cho header
    $totalQuantity = $cart->items()->sum('quantity');

    return response()->json([
        'success' => true,
        'item_total' => number_format($itemTotal, 0, ',', '.') . '₫',
        'cart_total' => number_format($grandTotal, 0, ',', '.') . '₫',
        'total_quantity' => $totalQuantity,
        'total_amount' => number_format($grandTotal, 0, ',', '.') . '₫'
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

public function buyNow(Request $request)
{
    try {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập.'
            ], 401);
        }

        $variantId = $request->input('variant_id');
        $quantity = (int) $request->input('quantity', 1);

        if (!$variantId || $quantity < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.'
            ]);
        }
        
        // Kiểm tra variant có tồn tại không
        $variant = product_variants::find($variantId);
        if (!$variant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Biến thể không tồn tại.'
            ]);
        }

        // Lấy giỏ hàng hoặc tạo mới
        $cart = $user->cart;

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $item = $cart->items()->where('variant_id', $variantId)->first();
        
        // Kiểm tra tồn kho
        $currentQuantityInCart = $item ? $item->quantity : 0;
        $newTotalQuantity = $currentQuantityInCart + $quantity;
        
        if ($newTotalQuantity > $variant->stock_quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể thêm sản phẩm. Chỉ còn ' . $variant->stock_quantity . ' sản phẩm trong kho, bạn đã có ' . $currentQuantityInCart . ' trong giỏ hàng.'
            ]);
        }

        // Cập nhật hoặc thêm mới sản phẩm
        if ($item) {
            $item->quantity = $newTotalQuantity;
            $item->save();
        } else {
            $cart->items()->create([
                'variant_id' => $variantId,
                'quantity'   => $quantity,
            ]);
        }
        
        // Tính tổng số lượng và giá trị giỏ hàng
        $cart->load('items.variant');
        $totalQuantity = $cart->items()->sum('quantity');
        $totalAmount = $cart->items->sum(function ($item) {
            return $item->variant ? $item->quantity * $item->variant->price : 0;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'redirect_url' => route('cart.index'),
            'total_quantity' => $totalQuantity,
            'total_amount' => number_format($totalAmount, 0, ',', '.') . '₫'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Lỗi khi mua ngay: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'error',
            'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
        ], 500);
    }
}



}