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

    // 👉 Thêm phương thức này vào đây
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    // Flash thông báo vào session để JS biết cần mở popup login
    session()->flash('show_login_popup', true);
    session()->flash('message', 'Vui lòng đăng nhập để tiếp tục.');

    return redirect()->back(); // quay lại trang trước (ví dụ: /cart)
}

}
