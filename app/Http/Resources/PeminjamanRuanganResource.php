<?php

namespace App\Http\Resources;

use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanRuanganResource extends JsonResource
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
            'user' => User::find($this->user_id),
            'kode' => $this->kode,
            'ruangan' => Ruangan::find($this->ruangan_id),
            'tanggal' => $this->tanggal,
            'waktu_awal' => $this->waktu_awal,
            'waktu_akhir' => $this->waktu_akhir,
            'keperluan' => $this->keperluan,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'created_at' =>  date('d M Y', strtotime($this->created_at)),
            'updated_at' =>  date('d M Y', strtotime($this->updated_at))
        ];
    }
}
