<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yudisium extends Model
{
    use HasFactory;
    protected $table = 'yudisium';
    protected $fillable = [
        'periode',
        'tahun',
        'expired_at'
    ];

    protected $primaryKey = 'id';
}
