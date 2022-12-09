<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'no_telp' => $this->no_telp,
            'status_keaktifan' => $this->status,
            'jurusan' => $this->jurusan,
            'prodi' => $this->prodi,
            'angkatan' => $this->angkatan,
            'role' => $this->role,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
        ];
    }

}
