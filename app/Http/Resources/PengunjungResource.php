<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PengunjungResource extends JsonResource
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
            'user' => $this->user($this->user_id),
            'created_at' => date('d M Y', strtotime($this->created_at)),
        ];
    }

    public function user($id){
        $dataUser = User::select('id','name','email','role')->where('id',$id)->first();
        return $dataUser;
    }
}
