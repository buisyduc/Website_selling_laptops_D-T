<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException; // <- Thêm dòng này
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    // Nếu không phải JSON, bạn có thể flash thông báo để hiển thị modal ở view
    session()->flash('show_login_popup', true);
    session()->flash('message', 'Vui lòng đăng nhập để tiếp tục.');

    return redirect()->guest(route('login')); // hoặc redirect()->back() nếu bạn thực sự cần quay lại
}


}
