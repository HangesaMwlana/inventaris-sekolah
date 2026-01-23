<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // Mengunci nama tabel agar tidak berubah menjadi peminjamen
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id', 'barang_id', 'petugas_id', 'jumlah', 
        'tanggal_pinjam', 'tanggal_kembali', 'status', 'deskripsi'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function barang() { return $this->belongsTo(Barang::class); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}