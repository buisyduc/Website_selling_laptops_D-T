<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Lấy người dùng dựa trên email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra xem người dùng có tồn tại và mật khẩu có đúng không
        if ($user && Hash::check($request->password, $user->password)) {
            // Kiểm tra trạng thái tài khoản
            if ($user->active == 2) {
                return redirect()->route('admin.login')->with('message', 'Tài khoản không kích hoạt.');
            }

            Auth::login($user); // Đăng nhập người dùng

            // Kiểm tra vai trò và chuyển hướng tương ứng
            if ($user->role === 'admin') {
                return redirect()->route('admin.index'); // Đến trang admin
            } else {
                return redirect()->route('index',$user); // Đến trang người dùng
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ]);
    }
    public function signup(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',

        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),

        ]);

        return view('client/index')->with('message', 'Tạo tài khoản thành công');


    }

}
