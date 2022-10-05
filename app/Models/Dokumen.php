<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shetabit\Visitor\Traits\Visitable;
class Dokumen extends Model
{
    use HasFactory,Visitable;
    protected $table = 'dokumen';
    protected $guarded = [];

    protected $primaryKey = 'id';

    protected $casts = [ 
        'user_id' => 'integer', 
        'mata_kuliah_id' => 'integer', ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
