<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifikasiResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());        
        $Notifikasi = NotifikasiResource::collection($user->notifications);
        return $this->successResponse(['jumlah_notifikasi' => $user->notifications->count(), 'data' => $Notifikasi]);
    }

}
