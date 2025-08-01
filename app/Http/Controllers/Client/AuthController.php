<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->active == 2) {
                return redirect()->route('login')->with('message', 'Tài khoản chưa được kích hoạt.');
            }

            Auth::login($user);

            if ($user->role === 'admin') {
                return redirect()->route('admin.index')->with('success', 'Đăng nhập thành công');
            }

            return redirect()->route('index')->with('success', 'Đăng nhập thành công');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ]);
    }

    // Hiển thị form đăng ký
    public function showSignupForm()
    {
        return view('client.auth.signup'); // bạn cần tạo view này
    }

    // Xử lý đăng ký
    public function signup(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer', // gán mặc định
            'active'   => 1,
        ]);

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Tạo tài khoản thành công');
    }

    // Đăng xuất


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index'); // hoặc route('login') nếu muốn
    }
   public function purchaseOrder()
{
    $user = Auth::user();

    // Giả sử bạn có các trường dữ liệu sau trong bảng users hoặc các bảng liên quan:
    // $totalOrders = $user->orders()->count(); // Đếm tổng số đơn
    // $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount'); // Tổng tiền

    return view('client/account/purchase_order', compact('user'));
}

}
