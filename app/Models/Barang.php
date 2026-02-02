<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok_total',
        'stok_tersedia',
        'deskripsi',
    ];

    // Memastikan tipe data angka tetap konsisten
    protected $casts = [
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
    ];

    /**
     * Relasi ke tabel Peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}