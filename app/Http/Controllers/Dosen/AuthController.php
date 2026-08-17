<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') return redirect()->route('admin.dashboard');
            if (Auth::user()->role === 'dosen') {
                if (Auth::user()->dosen->first_login) {
                    return redirect()->route('dosen.password.setup');
                }
                return redirect()->route('dosen.dashboard');
            }
        }

        $dosens = Dosen::all();
        return view('dosen.login', compact('dosens'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'password' => 'required',
        ]);

        $dosen = Dosen::find($request->dosen_id);
        if (!$dosen || !$dosen->user) {
            return back()->withErrors(['dosen_id' => 'Akun dosen tidak valid.']);
        }

        if (Auth::attempt(['email' => $dosen->user->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            if ($dosen->first_login) {
                return redirect()->route('dosen.password.setup');
            }
            
            return redirect()->intended(route('dosen.dashboard', absolute: false));
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ]);
    }
}
