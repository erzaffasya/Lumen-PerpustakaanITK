<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganBaca extends Model
{
    use HasFactory;
    protected $table = 'ruangan_baca';
    protected $fillable = [
        'kode',
        'ruangan',
    ];

    protected $primaryKey = 'id';

}
