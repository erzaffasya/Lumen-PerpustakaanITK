<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shetabit\Visitor\Traits\Visitable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory, Visitable, SoftDeletes;
    protected $table = 'dokumen';
    protected $guarded = [];

    protected $primaryKey = 'id';

    protected $casts = [
        'user_id' => 'integer',
        'mata_kuliah_id' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function peminjamanDokumen()
    {
        return $this->hasMany(PeminjamanDokumen::class);
    }

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($peminjamanDokumen) {
            $peminjamanDokumen->peminjamanDokumen()->delete();
        });
        static::deleted(function ($bookmark) {
            $bookmark->bookmark()->delete();
        });
        static::deleted(function ($pembimbing) {
            $pembimbing->pembimbing()->delete();
        });
    }
}
