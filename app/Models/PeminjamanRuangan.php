<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;
    protected $table = 'peminjaman_ruangan';
    protected $fillable = [
        'user_id',
        'kursi_baca_id',
        'tanggal_peminjaman',
    ];

    protected $primaryKey = 'id';

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function KursiBaca()
    {
        return $this->belongsTo(KursiBaca::class, 'kursi_baca_id', 'id');
    }
}
