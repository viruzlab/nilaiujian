<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with(['pembimbing1', 'pembimbing2'])->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $dosens = Dosen::all();
        return view('admin.mahasiswa.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:50|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'judul_skripsi' => 'nullable|string|max:1000',
            'pembimbing_1_id' => 'nullable|exists:dosens,id',
            'pembimbing_2_id' => 'nullable|exists:dosens,id|different:pembimbing_1_id',
            'jumlah_mutu' => 'nullable|numeric',
            'jumlah_sks' => 'nullable|integer',
            'mata_kuliah_ulang' => 'nullable|integer',
            'semester' => 'nullable|string|max:50',
            'ipk' => 'nullable|numeric|max:4.00',
        ]);

        Mahasiswa::create($request->only(
            'nim', 'nama', 'judul_skripsi', 'pembimbing_1_id', 'pembimbing_2_id',
            'jumlah_mutu', 'jumlah_sks', 'mata_kuliah_ulang', 'semester', 'ipk'
        ));

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $dosens = Dosen::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosens'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'judul_skripsi' => 'nullable|string|max:1000',
            'pembimbing_1_id' => 'nullable|exists:dosens,id',
            'pembimbing_2_id' => 'nullable|exists:dosens,id|different:pembimbing_1_id',
            'jumlah_mutu' => 'nullable|numeric',
            'jumlah_sks' => 'nullable|integer',
            'mata_kuliah_ulang' => 'nullable|integer',
            'semester' => 'nullable|string|max:50',
            'ipk' => 'nullable|numeric|max:4.00',
        ]);

        $mahasiswa->update($request->only(
            'nim', 'nama', 'judul_skripsi', 'pembimbing_1_id', 'pembimbing_2_id',
            'jumlah_mutu', 'jumlah_sks', 'mata_kuliah_ulang', 'semester', 'ipk'
        ));

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MahasiswaImport, $request->file('file'));
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->route('admin.mahasiswa.index')->withErrors(['file' => 'Gagal mengimpor file: ' . $e->getMessage()]);
        }
    }
}
