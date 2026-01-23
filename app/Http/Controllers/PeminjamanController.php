<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Mengambil data untuk tabel list peminjaman
        $peminjamans = Peminjaman::with(['user', 'barang'])->latest()->get();

        // MENGATASI ERROR: Undefined variable $barangs
        $barangs = Barang::where('stok', '>', 0)->get();

        // MENGATASI ERROR: Undefined variable $siswas
        $siswas = User::where('role', 'siswa')->get();

        return view('peminjamans.index', compact('peminjamans', 'barangs', 'siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        // Logika Input: Jika petugas yang input, ambil user_id dari form. Jika siswa, ambil Auth::id()
        $user_id = Auth::user()->role === 'petugas' ? $request->user_id : Auth::id();

        Peminjaman::create([
            'user_id' => $user_id,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => Auth::user()->role === 'petugas' ? 'disetujui' : 'pending',
            'petugas_id' => Auth::user()->role === 'petugas' ? Auth::id() : null,
        ]);

        // Jika petugas yang menginput, stok langsung dikurangi
        if (Auth::user()->role === 'petugas') {
            $barang->decrement('stok', $request->jumlah);
        }

        return redirect()->route('peminjamans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        if ($barang->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi untuk disetujui.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'petugas_id' => Auth::id()
        ]);

        $barang->decrement('stok', $peminjaman->jumlah);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function return($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()
        ]);

        Barang::find($peminjaman->barang_id)->increment('stok', $peminjaman->jumlah);

        return back()->with('success', 'Barang berhasil dikembalikan.');
    }
}