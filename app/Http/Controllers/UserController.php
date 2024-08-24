<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Node\FunctionNode;

class UserController extends Controller
{
    public function profile()
    {
        return view('pages.profile');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Lấy dữ liệu đầu vào
        $credentials = $request->only('email', 'password');

        // Xử lý xác thực
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công, chuyển hướng đến trang chính
            return redirect()->intended('dashboard');
        }

        // Đăng nhập thất bại, chuyển hướng về trang đăng nhập với lỗi
        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function logout()
    {
        Auth::logout(); // Đăng xuất người dùng
        return redirect('/'); // Chuyển hướng đến trang đăng nhập
    }
}
