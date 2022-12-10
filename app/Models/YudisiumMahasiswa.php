<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YudisiumMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'yudisium_mahasiswa';
    protected $fillable = [
        'user_id',
        'yudisium_id',
        'status_berkas',
        'status_pinjam',
        'status_final',
    ];

    protected $primaryKey = 'id';

    protected $casts = [
        'yudisium_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function yudisium()
    {
        return $this->belongsTo(Yudisium::class, 'yudisium_id', 'id');
    }
}
