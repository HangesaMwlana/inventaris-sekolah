<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Sekolah</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .kop-surat h2 { margin: 0; text-transform: uppercase; }
        .kop-surat p { margin: 2px 0; font-size: 12px; }
        
        .stat-box { margin-bottom: 20px; width: 100%; }
        .stat-box td { background: #f9f9f9; border: 1px solid #ddd; padding: 10px; text-align: center; font-size: 12px; }
        
        table.data { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.data th { background: #4f46e5; color: white; padding: 10px; border: 1px solid #ddd; }
        table.data td { padding: 8px; border: 1px solid #ddd; }
        .text-center { text-align: center; }
        
        .ttd-container { margin-top: 40px; float: right; width: 250px; text-align: center; font-size: 12px; }
        .status-pill { padding: 2px 6px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 9px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>Laporan Peminjaman Barang Inventaris</h2>
        <p>SMK Negeri Teknologi Informatika | Jl. Pendidikan No. 456</p>
        <p>Email: admin@sekolah.sch.id | Telp: (021) 888999</p>
    </div>

    <table class="stat-box">
        <tr>
            <td>Total Transaksi: <strong>{{ $statistik['total'] }}</strong></td>
            <td>Disetujui: <strong>{{ $statistik['disetujui'] }}</strong></td>
            <td>Dikembalikan: <strong>{{ $statistik['kembali'] }}</strong></td>
            <td>Menunggu: <strong>{{ $statistik['pending'] }}</strong></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->barang->kode_barang }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->user->name }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-center">{{ $item->tanggal_pinjam }}</td>
                <td class="text-center">{{ $item->tanggal_kembali ?? '-' }}</td>
                <td class="text-center">{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
        <p>Mengetahui,</p>
        <p>Kepala Bagian Sarpras</p>
        <br><br><br>
        <p><strong>( __________________________ )</strong></p>
        <p>NIP. ...........................</p>
    </div>
</body>
</html>