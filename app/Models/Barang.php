<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['nama_barang', 'kode_barang', 'stok', 'deskripsi'];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}