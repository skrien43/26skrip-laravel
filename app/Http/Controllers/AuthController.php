<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm() {
        $num1 = rand(1, 49);
        $num2 = rand(1, 50 - $num1);
        session(['captcha_answer' => $num1 + $num2]);
        return view('auth.index', compact('num1', 'num2'));
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha'  => 'required|integer',
        ]);
        if ((int) $request->captcha !== (int) session('captcha_answer')) {
            session()->forget('captcha_answer');
            return redirect('/login')->with('error', 'Captcha tidak benar!');
        }

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return redirect('/login')->with('error', 'Username tidak terdaftar!');
        }

        if (Hash::check($request->password, $user->password)) {
            session()->forget('captcha_answer');
            session([
                'user_id'  => $user->id_user,
                'username' => $user->username,
                'role'     => $user->us_role,
            ]);

            $redirect = match ($user->us_role) {
                'dsn'   => route('dashboard.peminjaman'),
                'mhs'   => route('dashboard.peminjaman'),
                'adm'   => route('admin.peminjaman'),
                default => route('login'),
            };
            return redirect($redirect);
        }
        return redirect('/login')->with('error', 'Password tidak benar!');
    }

    public function logout(Request $request) {
        $request->session()->forget(['user_id', 'username', 'role']);
        return redirect('/');
    }
}
