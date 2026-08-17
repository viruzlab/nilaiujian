<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function setup()
    {
        // Hanya tampilkan jika first_login bernilai true
        if (!Auth::user()->dosen->first_login) {
            return redirect()->route('dosen.dashboard');
        }

        return view('dosen.setup-password');
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $dosen = $user->dosen;

        // Update password di User
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Update status first_login dan hapus password_plain
        $dosen->update([
            'first_login' => false,
            'password_plain' => null
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Password Anda berhasil diperbarui. Selamat datang di Dashboard!');
    }
}
