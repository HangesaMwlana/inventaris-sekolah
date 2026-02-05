<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .stat-table { width: 100%; margin-bottom: 15px; border: 1px solid #eee; background: #f9f9f9; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #4f46e5; color: white; padding: 8px; border: 1px solid #ddd; }
        td { padding: 6px; border: 1px solid #ddd; }
        .text-center { text-align: center; }
        .badge-danger { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">DAFTAR INVENTARIS BARANG</h2>
        <p style="margin:5px 0;">SMK INVENTARIS JAYA | Laporan Stok Per Tanggal {{ $statistik['date'] }}</p>
    </div>

    <div class="stat-table">
        Total Jenis Barang: <strong>{{ $statistik['total_jenis'] }}</strong> | 
        Total Seluruh Item (Tersedia): <strong>{{ $statistik['total_item'] }}</strong> | 
        Stok Kosong: <span class="badge-danger">{{ $statistik['out_of_stock'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th>Nama Barang</th>
                <th width="12%">Stok Tersedia</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $key => $b)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td class="text-center" @style(['color:red; font-weight:bold;' => $b->stok_tersedia <= 5])>
                    {{ $b->stok_tersedia }}
                </td>
                <td>{{ $b->deskripsi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh:</p>
        <br><br><br>
        <p><strong>{{ $statistik['printed_by'] }}</strong></p>
        <p style="font-size: 9px;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>