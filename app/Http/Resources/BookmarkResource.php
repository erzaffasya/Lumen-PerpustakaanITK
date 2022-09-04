<?php

namespace App\Http\Resources;

use App\Models\Dokumen;
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
            'user_id' => $this->user_id,
            // 'dokumen_id' => $this->dokumen_id,
            'dokumen' => $this->dokumen($this->dokumen_id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function dokumen($id){
        $data = Dokumen::find($id);
        return $data;
    }
}
