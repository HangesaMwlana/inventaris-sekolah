<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            // Data khusus untuk Dashboard Siswa
            $totalPinjamSaya = Peminjaman::where('user_id', $user->id)->where('status', 'disetujui')->count();
            $totalPendingSaya = Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count();
            $totalTerlambatSaya = Peminjaman::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
                ->count();

            return view('dashboard', compact('totalPinjamSaya', 'totalPendingSaya', 'totalTerlambatSaya'));
        }

        // Data khusus untuk Dashboard Petugas
        $totalStokBarang = Barang::sum('stok_total');
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalPinjam = Peminjaman::where('status', 'disetujui')->count();
        $totalPending = Peminjaman::where('status', 'pending')->count();
        $totalTerlambat = Peminjaman::where('status', 'disetujui')
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();

        // Data Grafik Bulanan (Petugas Only)
        $rawChartData = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartData = collect(range(1, 12))->map(function ($month) use ($rawChartData) {
            return [
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total' => $rawChartData->get($month, 0)
            ];
        });

        return view('dashboard', compact(
            'totalStokBarang', 'totalSiswa', 'totalPinjam', 
            'totalPending', 'totalTerlambat', 'chartData'
        ));
    }
}