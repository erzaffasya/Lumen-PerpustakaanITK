<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YudisiumResource extends JsonResource
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
            'periode' => $this->periode,
            'tahun' => $this->tahun,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
