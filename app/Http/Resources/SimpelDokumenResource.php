<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'kategori' => $this->nama_kategori,
            'penerbit' => $this->penerbit
        ];
    }
}
