<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang dengan fitur search & filter kategori
     */
    public function index(Request $request)
    {
        $query = Barang::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $barangs = $query->latest()->paginate(10);
        return view('barangs.index', compact('barangs'));
    }

    /**
     * Menyimpan barang baru dengan generate kode otomatis (BRG-00X)
     */
    public function store(Request $request)
    {
        // Hanya Petugas yang bisa menambah barang
        if (Auth::user()->role !== 'petugas') {
            return back()->with('error', 'Hanya petugas yang boleh menambah barang.');
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|in:elektronik,atk,alat_kebersihan,lainnya',
            'stok_input'  => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate Kode Barang Otomatis
            $maxId = Barang::max('id') ?? 0;
            $nextNumber = $maxId + 1;
            $kodeBarang = 'BRG-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            Barang::create([
                'kode_barang'   => $kodeBarang,
                'nama_barang'   => $request->nama_barang,
                'kategori'      => $request->kategori,
                'stok_total'    => $request->stok_input,
                'stok_tersedia' => $request->stok_input,
                'deskripsi'     => $request->deskripsi,
            ]);

            DB::commit();
            return redirect()->route('barangs.index')->with('success', "Barang berhasil ditambahkan dengan kode: $kodeBarang");
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambah barang: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data barang & sinkronisasi stok_tersedia
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|in:elektronik,atk,alat_kebersihan,lainnya',
            'stok_total'  => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        try {
            // Hitung selisih perubahan stok total
            $selisih = $request->stok_total - $barang->stok_total;
            
            // Validasi: Stok tersedia tidak boleh minus setelah dikurangi
            if (($barang->stok_tersedia + $selisih) < 0) {
                return back()->with('error', 'Gagal update! Stok tersedia tidak mencukupi (barang sedang banyak dipinjam).');
            }

            $barang->update([
                'nama_barang'   => $request->nama_barang,
                'kategori'      => $request->kategori,
                'stok_total'    => $request->stok_total,
                'stok_tersedia' => $barang->stok_tersedia + $selisih,
                'deskripsi'     => $request->deskripsi,
            ]);

            return redirect()->route('barangs.index')->with('success', 'Data barang berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Menghapus barang (Hanya jika tidak ada transaksi aktif)
     */
    public function destroy(Barang $barang)
    {
        // Cek apakah barang sedang dipinjam (status pending atau disetujui)
        $isUsed = $barang->peminjamans()
                        ->whereIn('status', ['pending', 'disetujui'])
                        ->exists();

        if ($isUsed) {
            return back()->with('error', 'Barang tidak bisa dihapus karena masih dalam transaksi aktif!');
        }
        
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Mencetak laporan barang ke PDF
     */
    public function cetakLaporan(Request $request)
    {
        $query = Barang::query();
        
        // Filter berdasarkan kondisi stok
        if ($request->filter_stok === 'habis') {
            $query->where('stok_tersedia', 0);
        } elseif ($request->filter_stok === 'sedikit') {
            $query->whereBetween('stok_tersedia', [1, 5]);
        } elseif ($request->filter_stok === 'tersedia') {
            $query->where('stok_tersedia', '>', 5);
        }

        $barangs = $query->orderBy('nama_barang')->get();
        
        $statistik = [
            'total_jenis'  => $barangs->count(),
            'total_item'   => $barangs->sum('stok_tersedia'),
            'out_of_stock' => $barangs->where('stok_tersedia', 0)->count(),
            'printed_by'   => Auth::user()->name,
            'date'         => now()->format('d F Y H:i')
        ];

        $pdf = Pdf::loadView('barangs.laporan_pdf', compact('barangs', 'statistik'));
        return $pdf->stream('Laporan_Stok_Barang.pdf');
    }
}