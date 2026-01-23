<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_barang' => Barang::sum('stok') ?? 0,
            // Pastikan 'disetujui' diapit tanda kutip
            'total_pinjam' => Peminjaman::where('status', 'disetujui')->count() ?? 0,
            'total_siswa'  => User::where('role', 'siswa')->count() ?? 0,
            'chart_data'   => Peminjaman::select(
                                    DB::raw("COUNT(*) as count"), 
                                    DB::raw("MONTHNAME(created_at) as month")
                                )
                                ->whereYear('created_at', date('Y'))
                                ->groupBy('month')
                                ->get()
        ];

        return view('dashboard', $data);
    }
}