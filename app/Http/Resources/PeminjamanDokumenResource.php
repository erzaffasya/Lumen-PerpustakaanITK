<?php

namespace App\Http\Resources;

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
            'nama' => $this->user_id,
            'tgl_peminjaman' => date('d-m-Y', strtotime($this->tgl_peminjaman)),
            'tgl_pengembalian' => date('d-m-Y', strtotime($this->tgl_pengembalian)),
            'status' => $this->status,
            'dokumen' => $this->dokumen->judul,
            'durasi_peminjaman' => $this->jauh_hari($this->tgl_peminjaman)
        ];
    }

    public function jauh_hari($tgl_peminjaman)
    {
        if ($this->status != True) {
            $selisih_hari = $tgl_peminjaman->diff(Carbon::now());
            return $selisih_hari->format('%r%a Hari');
        } else {
            return 'Sudah Kembali pada ' . date('d-m-Y', strtotime($this->tgl_pengembalian));
        }
    }
}
