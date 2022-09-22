<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriResource extends JsonResource
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
            'nama_kategori' => $this->nama_kategori,
            'detail' => $this->detail,
            'berkas' => json_decode($this->berkas),
            'isPembimbing' => $this->isPembimbing,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
