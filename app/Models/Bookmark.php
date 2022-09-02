<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;
    protected $table = 'bookmark';
    protected $fillable = [
        'dokumen_id',
        'user_id',
    ];

    protected $primaryKey = 'id';

    protected $casts = [ 
        'dokumen_id' => 'integer', 
        'user_id' => 'integer', ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'id');
    }
}
