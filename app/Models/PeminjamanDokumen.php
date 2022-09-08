<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDokumen extends Model
{
    use HasFactory;
    protected $table = 'peminjaman_dokumen';
    protected $fillable = [
        'dokumen_id',
        'user_id',
        'tgl_pengembalian',
        'tgl_peminjaman',
        'status',
    ];

    protected $primaryKey = 'id';

    protected $casts = [
        'user_id' => 'integer',
        'dokumen_id' => 'integer',
        'tgl_pengembalian' => 'datetime',
        'tgl_peminjaman' => 'datetime',
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
