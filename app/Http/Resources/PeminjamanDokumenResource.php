<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanDokumenResource extends JsonResource
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
            'user' => $this->user($this->user_id),
            'gambar_dokumen' => url($this->getURL($this->dokumen_id)),
            'tgl_peminjaman' => date('d-m-Y', strtotime($this->tgl_peminjaman)),
            'tgl_pengembalian' => date('d-m-Y', strtotime($this->tgl_pengembalian)),
            'dokumen' => $this->dokumen($this->dokumen_id),
            'durasi_peminjaman' => $this->jauh_hari($this->tgl_pengembalian)
        ];
    }

    public function jauh_hari($tgl_pengembalian)
    {
        if ($this->tgl_pengembalian > Carbon::now()) {
            $selisih_hari = $tgl_pengembalian->diff(Carbon::now());
            return $selisih_hari->Format('%h Jam %i Menit');
        } else {
            return 'Sudah Kembali pada ' . date('d-m-Y', strtotime($this->tgl_pengembalian));
        }
    }

    public function getURL($id)
    {
        $getDokumen = Dokumen::where('id',$id)->first();
        return $getDokumen->gambar_dokumen;
    }
    public function user($id)
    {
        $getUser = User::select('id', 'name', 'email', 'role')
            ->where('id', $id)
            ->first();
        return $getUser;
    }
    public function dokumen($id)
    {
        $getDokumen = Dokumen::select('dokumen.id', 'dokumen.judul', 'dokumen.tahun_terbit', 'dokumen.penerbit', 'dokumen.status', 'kategori.nama_kategori')
            ->join('kategori', 'kategori.id', 'dokumen.kategori_id')
            ->where('dokumen.id', $id)->first();
        return $getDokumen;
    }
}
