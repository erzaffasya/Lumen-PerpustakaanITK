<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $fillable = [
        'nama_ruangan',
        'deskripsi',
        'jumlah_orang',
        'lokasi',
    ];

    protected $primaryKey = 'id';

}
