<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =========================
       RELATIONSHIPS
    ========================= */

    /**
     * Relasi: user (siswa) sebagai peminjam
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi: user (petugas/admin) sebagai penyetuju
     */
    public function peminjamanDiproses()
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }

    /* =========================
       ROLE HELPERS
    ========================= */

    // Helper untuk cek role di Controller / Blade
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
