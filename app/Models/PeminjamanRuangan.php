<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;
    protected $table = 'peminjaman_ruangan';
    protected $fillable = [
        'kode',
        'user_id',
        'ruangan_id',
        'tanggal',
        'waktu_awal',
        'waktu_akhir',
        'keperluan',
        'status',
        'catatan'
    ];

    protected $primaryKey = 'id';

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function Ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id');
    }
}
