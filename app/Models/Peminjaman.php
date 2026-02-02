<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans'; 

    protected $fillable = [
        'user_id',
        'barang_id',
        'petugas_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_jatuh_tempo',
        'status',
        'deskripsi' // Menggantikan tujuan_peminjaman
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}