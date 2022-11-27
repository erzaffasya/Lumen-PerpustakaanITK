<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
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
            'user_id' => $this->user($this->user_id),
            // 'dokumen_id' => $this->dokumen_id,
            'dokumen' => $this->dokumen($this->dokumen_id),
            'gambar_dokumen' => url($this->getURL($this->dokumen_id)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function dokumen($id){
        $data = Dokumen::select('id','judul','tahun_terbit','nama_pengarang','kategori_id','created_at','updated_at')->find($id);
        return $data;
    }

    public function user($id){
        $data = User::select('name','email','nim','jurusan','prodi','angkatan','role')->find($id);
        return $data;
    }
    public function getURL($id)
    {
        $getDokumen = Dokumen::where('id',$id)->first();
        return $getDokumen->gambar_dokumen;
    }
}
