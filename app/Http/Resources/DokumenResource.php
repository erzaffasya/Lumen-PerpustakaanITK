<?php

namespace App\Http\Resources;

use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\Kategori;
use App\Models\Pembimbing;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DokumenResource extends JsonResource
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
            'kategori' => $this->kategori($this->kategori_id),
            'tahun_terbit' => $this->tahun_terbit,
            'nama_pengarang' => $this->judul,
            'penerbit' => $this->penerbit,
            'gambar_dokumen' => url($this->gambar_dokumen),
            'deskripsi' => $this->deskripsi,
            'user_id' => $this->users->name,
            'nim' => $this->users->nim,
            'cover' => $this->FormatFile($this->cover),
            'lembar_pengesahan' => $this->FormatFile($this->lembar_pengesahan),
            'kata_pengantar' => $this->FormatFile($this->kata_pengantar),
            'ringkasan' => $this->FormatFile($this->ringkasan),
            'daftar_isi' => $this->FormatFile($this->daftar_isi),
            'daftar_gambar' => $this->FormatFile($this->daftar_gambar),
            'daftar_tabel' => $this->FormatFile($this->daftar_tabel),
            'daftar_notasi' => $this->FormatFile($this->daftar_notasi),
            'abstract_en' => $this->FormatFile($this->abstract_en),
            'abstract_id' => $this->FormatFile($this->abstract_id),
            'bab1' => $this->FormatFile($this->bab1),
            'bab2' => $this->FormatFile($this->bab2),
            'bab3' => $this->FormatFile($this->bab3),
            'bab4' => $this->FormatFile($this->bab4),
            'kesimpulan' => $this->FormatFile($this->kesimpulan),
            'daftar_pustaka' => $this->FormatFile($this->daftar_pustaka),
            'lampiran' => $this->FormatFile($this->lampiran),
            'paper' => $this->FormatFile($this->paper),
            'lembar_persetujuan' => $this->FormatFile($this->lembar_persetujuan),
            'full_dokumen' => $this->FormatFile($this->full_dokumen),
            'status' => $this->status,
            'data_tambahan' => $this->data_tambahan,
            'jurusan' => $this->users->jurusan,
            'tanggal_dibuat' => date('d M Y', strtotime($this->created_at)),
            'isBookmark' => $this->Bookmark($this->id),
            'jumlah_kunjungan' => $this->jumlahPengunjung($this->id),
            'pembimbing' => $this->pembimbing($this->id),
            'isPinjam' => $this->isPinjam($this->id)
        ];
    }
    public function FormatFile($data)
    {
        if ($data) {
            $format = [
                'file' => $data,
                'size' => $this->FileSize($data)
            ];
            return $format;
        } else {
            return null;
        }
    }

    public function kategori($id){
        $kategori = Kategori::select('id','nama_kategori')->where('id',$id)->first();
        return $kategori;
    }

    public function FileSize($data)
    {
        if ($data) {
            $fileSize = File::size($data);
            return $this->FormatUkuranFile($fileSize);
        } else {
            $fileSize = null;
        }
    }

    public function FormatUkuranFile($data)
    {
        if ($data >= 1073741824) {
            $data = number_format($data / 1073741824, 2) . ' GB';
        } elseif ($data >= 1048576) {
            $data = number_format($data / 1048576, 2) . ' MB';
        } elseif ($data >= 1024) {
            $data = number_format($data / 1024, 2) . ' KB';
        } elseif ($data > 1) {
            $data = $data . ' bytes';
        } elseif ($data == 1) {
            $data = $data . ' byte';
        } else {
            $data = '0 bytes';
        }

        return $data;
    }

    public function Bookmark($dokumen){
        $cekBookmark = Bookmark::where('user_id',Auth::user()->id)->where('dokumen_id',$dokumen)->exists();
        return $cekBookmark;
    }

    public function jumlahPengunjung($id){
        $jumlahPengunjung = Dokumen::find($id);
        return $jumlahPengunjung->visitLogs()->count();
    }

    public function pembimbing($id){
        $dataPembimbing = Pembimbing::where('dokumen_id',$id)->get();
        if(count($dataPembimbing) > 0){
            $getData = UserResource::collection(User::whereIn('id',$dataPembimbing->pluck('user_id'))->get());
        }else{
            $getData = null;
        }
        return $getData;
    }

    public function isPinjam($id){
        $dataPinjam = PeminjamanDokumen::where('dokumen_id',$id)->where('tgl_pengembalian','>',Carbon::now())->exists();
        return $dataPinjam;
    }
}
