<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // --- LOGIKA OTOMATIS AGAR DATA TIDAK NUMPUK ---
        // Kita tentukan batas waktu 2 bulan yang lalu
        $batasWaktu = Carbon::now()->subMonths(2);

        $query = Peminjaman::with(['user', 'barang']);

        // Filter Otomatis: 
        // Tampilkan semua yang BELUM kembali (biar terpantau terus)
        // DAN hanya tampilkan riwayat kembali yang usianya di bawah 2 bulan
        $query->where(function ($q) use ($batasWaktu) {
            $q->where('status', '!=', 'dikembalikan')
              ->orWhere('tanggal_kembali', '>=', $batasWaktu);
        });

        // Fitur Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('barang', fn ($b) => $b->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $peminjamans = $query->latest()->paginate(10);
        $barangs = Barang::where('stok_tersedia', '>', 0)->get();
        $siswas  = User::where('role', 'siswa')->get();

        return view('peminjamans.index', compact('peminjamans', 'barangs', 'siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $user = Auth::user();
        $isPetugas = $user->role === 'petugas';
        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok_tersedia < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        $peminjamId = $isPetugas ? $request->user_id : $user->id;

        Peminjaman::create([
            'user_id' => $peminjamId,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'deskripsi' => $request->deskripsi,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => $isPetugas ? 'disetujui' : 'pending',
            'petugas_id' => $isPetugas ? $user->id : null,
        ]);

        if ($isPetugas) {
            $barang->decrement('stok_tersedia', $request->jumlah);
        }

        return back()->with('success', $isPetugas ? 'Peminjaman berhasil dicatat.' : 'Pengajuan berhasil dikirim.');
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Data yang sudah dikembalikan tidak dapat diubah.');
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);
        
        $barang = $peminjaman->barang;
        
        if ($peminjaman->status === 'disetujui') {
            $selisih = $request->jumlah - $peminjaman->jumlah;
            if ($barang->stok_tersedia < $selisih) {
                return back()->with('error', 'Stok tidak mencukupi untuk perubahan ini.');
            }
            $barang->decrement('stok_tersedia', $selisih);
        }

        $peminjaman->update([
            'jumlah' => $request->jumlah,
            'deskripsi' => $request->deskripsi,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo
        ]);

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    // METHOD DESTROY: Mendukung hapus satu data DAN hapus semua riwayat (Clear All)
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();

        // Fitur "Clear All" (ID 'all' dikirim dari tombol di Blade)
        if ($id === 'all') {
            if ($user->role !== 'petugas') {
                return back()->with('error', 'Hanya petugas yang bisa membersihkan riwayat.');
            }

            Peminjaman::where('status', 'dikembalikan')->delete();
            return back()->with('success', 'Semua riwayat pengembalian telah dihapus dari database.');
        }

        // Hapus Satuan
        $peminjaman = Peminjaman::findOrFail($id);

        if ($user->role === 'siswa' && $peminjaman->user_id !== $user->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($peminjaman->status === 'disetujui') {
            return back()->with('error', 'Barang harus dikembalikan dulu sebelum data dihapus.');
        }

        $peminjaman->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = $peminjaman->barang;

        if ($barang->stok_tersedia < $peminjaman->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'petugas_id' => Auth::id()
        ]);

        $barang->decrement('stok_tersedia', $peminjaman->jumlah);
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function return($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Status tidak valid.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => Carbon::now()
        ]);

        $peminjaman->barang->increment('stok_tersedia', $peminjaman->jumlah);
        return back()->with('success', 'Barang berhasil dikembalikan.');
    }

    public function cetakLaporan(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang', 'petugas']);

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $laporan = $query->latest()->get();

        $statistik = [
            'total' => $laporan->count(),
            'disetujui' => $laporan->where('status', 'disetujui')->count(),
            'kembali' => $laporan->where('status', 'dikembalikan')->count(),
            'pending' => $laporan->where('status', 'pending')->count(),
        ];

        $pdf = Pdf::loadView('peminjamans.laporan_pdf', compact('laporan', 'statistik'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Peminjaman-' . Carbon::now()->format('d-m-Y') . '.pdf');
    }
}