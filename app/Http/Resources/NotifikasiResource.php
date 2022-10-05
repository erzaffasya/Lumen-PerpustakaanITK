<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
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
            'judul' => $this->user_id,
            'pesan' => $this->data,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
