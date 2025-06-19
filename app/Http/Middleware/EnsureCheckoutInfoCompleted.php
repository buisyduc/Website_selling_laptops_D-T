<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCheckoutInfoCompleted
{
public function handle($request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'customer') {
        return $next($request);
    }

    abort(403, 'Bạn không có quyền truy cập.');
}
}
