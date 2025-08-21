<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsCustomer
{
    public function handle(Request $request, Closure $next)
    {
        // Ví dụ: kiểm tra nếu user có role là "customer"
        if (Auth::check() && Auth::user()->role === 'customer') {
            return $next($request);
        }

        // Nếu không phải customer thì chuyển về trang login hoặc báo lỗi
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập bằng tài khoản khách hàng!');
    }
}
