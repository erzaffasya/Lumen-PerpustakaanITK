<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PembimbingResource extends JsonResource
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
            'nama_pembimbing' => new UserResource(User::find($this->user_id)) ,
            'dokumen' => new SimpelDokumenResource(Dokumen::find($this->dokumen_id)),
        ];
    }


}
