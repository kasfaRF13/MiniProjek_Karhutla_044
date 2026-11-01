<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Pengarahan halaman otomatis berdasarkan role (Access Matrix)
            if ($user->role === 'pelapor') {
                return redirect()->route('reports.create')->with('success', 'Selamat datang, Pelapor!');
            } elseif (in_array($user->role, ['relawan', 'koordinator'])) {
                return redirect()->route('reports.index')->with('success', 'Selamat datang, ' . ucfirst($user->role) . '!');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}