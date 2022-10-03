<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;
    protected $table = 'pengunjung';
    protected $fillable = [
        'user_id',
    ];

    protected $primaryKey = 'id';

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
