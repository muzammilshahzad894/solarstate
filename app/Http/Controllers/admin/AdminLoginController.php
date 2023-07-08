<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Email is invalid',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 6 characters'
            ]);

            $data = $request->all();
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                if (Auth::user()->role == 'user') {
                    Auth::logout();
                    return redirect()->back()->with('error', 'Invalid Username or Password');
                }
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid email or password');
            }
        }

        return view('admin.auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
