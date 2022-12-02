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

class SimpelDokumenResource extends JsonResource
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
            'isBookmark' => $this->Bookmark($this->id),
            'jumlah_kunjungan' => $this->jumlahPengunjung($this->id),
            'isPinjam' => $this->isPinjam($this->id)
        ];
    }

    public function Bookmark($dokumen){
        $cekBookmark = Bookmark::where('user_id',Auth::user()->id)->where('dokumen_id',$dokumen)->exists();
        return $cekBookmark;
    }
    
    public function Kategori($id){
        $dataKategori = Kategori::find($id);
        return $dataKategori->nama_kategori;
    }

    public function jumlahPengunjung($id){
        $jumlahPengunjung = Dokumen::find($id);
        return $jumlahPengunjung->visitLogs()->count();
    }

    public function isPinjam($id){
        $dataPinjam = PeminjamanDokumen::where('dokumen_id',$id)->where('tgl_pengembalian','>',Carbon::now())->exists();
        return $dataPinjam;
    }
}
