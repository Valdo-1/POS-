<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // tampilin form login
    public function loginView()
    {
        // kalau udah login, ngapain buka form login lagi, langsung arahin ke halamannya
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('login');
    }

    // proses autentikasi login user
    public function action_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // coba cocokkan email & password
        if (Auth::attempt($credentials)) {
            // regenerate session ID buat cegah serangan session fixation
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        // kalau gagal, balikin ke form login tapi jangan ilangin ketikan emailnya
        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    // bersihin sesi pas user mau keluar
    public function logout(Request $request)
    {
        Auth::logout();
        // hangusin data session dan reset token CSRF biar ga bisa dipakai ulang
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar.');
    }

    // lempar user ke halaman pertama sesuai tugas rolenya masing-masing
    protected function redirectBasedOnRole($user)
    {
        // kasir langsung ke meja kasir POS biar cepet kerja
        if ($user->hasRole(['kasir', 'cashier'])) {
            return redirect()->route('transactions.index');
        }

        // bos/pimpinan langsung liat rekap omzet penjualan
        if ($user->hasRole(['pimpinan', 'leader'])) {
            return redirect()->route('reports.index');
        }

        // admin biasa default-nya masuk ke dashboard
        return redirect()->route('dashboard');
    }
}
