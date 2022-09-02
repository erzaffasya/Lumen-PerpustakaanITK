<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KursiBaca extends Model
{
    use HasFactory;
    protected $table = 'kursi_baca';
    protected $fillable = [
        'kode',
        'kursi',
        'ruangan_baca_id',
    ];

    protected $primaryKey = 'id';


    public function RuanganBaca()
    {
        return $this->belongsTo(RuanganBaca::class, 'ruangan_baca_id', 'id');
    }
}
