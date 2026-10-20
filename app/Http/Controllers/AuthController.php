<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user->role === 'dokter') {
                return redirect()->route('dokter.dashboard');
            } else {
                return redirect()->route('pasien.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|numeric|unique:users,no_ktp',
            'no_hp' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $month = now()->format('Ym');
        $lastSequence = User::query()
            ->where('role', 'pasien')
            ->where('no_rm', 'like', "{$month}-%")
            ->pluck('no_rm')
            ->map(fn (string $noRm) => (int) substr($noRm, 7))
            ->max() ?? 0;
        $noRm = $month . '-' . str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

        User::create([
            ...$validated,
            'no_rm' => $noRm,
            'role' => 'pasien',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
