<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KursiBacaResource extends JsonResource
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
            'kode' => $this->kode,
            'kursi' => $this->kursi,
            'ruangan_baca' => $this->RuanganBaca->ruangan,            
            'ruangan_baca_id' => $this->ruangan_baca_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
