<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCartNotEmpty
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('cart.index')->with('error', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $cart = $user->cart;

        // Nếu chưa có giỏ hàng hoặc giỏ hàng không có sản phẩm
        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        return $next($request);
    }
}
