<?php

namespace App\Http\Resources;

use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\Kategori;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use PDO;

class PeminjamanDokumenTerpopulerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'gambar_dokumen' => url($this->gambar_dokumen),
            'nama_pengarang' => $this->nama_pengarang,
            'kategori' => $this->Kategori($this->kategori_id),
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'tanggal_dibuat' => date('d M Y', strtotime($this->created_at)),
            'total_peminjaman' => $this->total_peminjaman
        ];
    }
    
    public function Kategori($id){
        $dataKategori = Kategori::find($id);
        return $dataKategori->nama_kategori;
    }

}
