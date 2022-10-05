<?php

namespace App\Http\Resources;

use App\Models\Bookmark;
use App\Models\Dokumen;
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
            'kategori_id' => $this->kategori->nama_kategori,
            'tahun_terbit' => $this->tahun_terbit,
            'nama_pengarang' => $this->judul,
            'penerbit' => $this->penerbit,
            'user_id' => $this->users->name,
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
            'jumlah_kunjungan' => $this->jumlahPengunjung($this->id)
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
}
