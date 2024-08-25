<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Node\FunctionNode;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

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
        Auth::logout(); 
        return redirect('/'); 
    }
}
