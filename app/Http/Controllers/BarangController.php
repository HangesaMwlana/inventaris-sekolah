<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('barangs.index', compact('barangs'));
    }

    // Fungsi untuk memproses tambah barang
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        // LOGIKA GENERATE KODE BARANG OTOMATIS (Contoh: BRG-001)
        $latestBarang = \App\Models\Barang::latest('id')->first();
        $nextNumber = $latestBarang ? $latestBarang->id + 1 : 1;
        $kodeBarang = 'BRG-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        \App\Models\Barang::create([
            'kode_barang' => $kodeBarang, // Sekarang kode_barang ikut disimpan
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan dengan kode ' . $kodeBarang);
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus!');
    }
}