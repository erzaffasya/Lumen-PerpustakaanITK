<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Yudisium;
use Illuminate\Http\Resources\Json\JsonResource;

class YudisiumMahasiswaResource extends JsonResource
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
            'yudisium' => new YudisiumResource((Yudisium::find($this->yudisium_id))),
            'user' => new UserResource(User::find($this->user_id)),
            'status_berkas' => $this->status_berkas,
            'status_pinjam' => $this->status_pinjam,
            'status_final' => $this->status_final,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

}
