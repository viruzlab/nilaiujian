<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with('user')->get();
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'nullable|string|max:50|unique:dosens,nidn',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nidn' => $request->nidn,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan!');
    }

    public function edit(Dosen $dosen)
    {
        $dosen->load('user');
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'nullable|string|max:50|unique:dosens,nidn,' . $dosen->id,
            'email' => 'required|email|unique:users,email,' . $dosen->user_id,
            'password' => 'nullable|string|min:6',
        ]);

        $dosen->update([
            'nama' => $request->nama,
            'nidn' => $request->nidn,
        ]);

        $userData = [
            'name' => $request->nama,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $dosen->user->update($userData);

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy(Dosen $dosen)
    {
        $user = $dosen->user;
        $dosen->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus!');
    }

    public function generatePasswords()
    {
        $dosens = Dosen::with('user')->get();
        foreach ($dosens as $dosen) {
            if ($dosen->user) {
                // Generate a random 6-character alphanumeric password
                $newPassword = \Illuminate\Support\Str::random(6);
                
                $dosen->user->update([
                    'password' => Hash::make($newPassword)
                ]);
                
                $dosen->update([
                    'password_plain' => $newPassword
                ]);
            }
        }

        return redirect()->route('admin.dosen.index')->with('success', 'Password untuk semua dosen berhasil di-generate ulang!');
    }

    public function downloadPasswords()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DosenPasswordExport, 'Daftar_Password_Dosen.xlsx');
    }
}
