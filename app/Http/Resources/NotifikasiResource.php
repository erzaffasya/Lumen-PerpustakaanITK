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
        if ($this->read_at) {
            $data = $this->read_at->diffForHumans();
        } else {
            $data = null;
        }
        
        return [
            'id' => $this->id,
            'judul' => $this->user_id,
            'pesan' => $this->data,
            'read_at' =>   $data,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
