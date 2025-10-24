<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return redirect()->route('mahasiswa.dashboard');
        }
        if (Auth::guard('dosen')->check()) {
            return redirect()->route('dosen.dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required|in:mahasiswa,dosen',
        ]);

        $credentials = [
            'password' => $request->password,
        ];

        if ($request->role === 'mahasiswa') {
            $credentials['nim'] = $request->username;
            
            if (Auth::guard('mahasiswa')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('mahasiswa.dashboard'));
            }
        } else {
            $credentials['nidn_dosen'] = $request->username;
            
            if (Auth::guard('dosen')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dosen.dashboard'));
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } elseif (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}