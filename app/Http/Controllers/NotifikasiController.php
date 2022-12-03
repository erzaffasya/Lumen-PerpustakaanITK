<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifikasiResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/notifikasi",
     *  tags={"Notifikasi"},
     *  summary="Get notifikasi",
     *  @OA\Response(response=200, description="Get notifikasi"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $Notifikasi = NotifikasiResource::collection($user->notifications);
        return $this->successResponse(['jumlah_notifikasi' => $user->notifications->count(), 'notifikasi_unread' => $user->unreadNotifications->count(), 'data' => $Notifikasi]);
    }

    public function bacaNotifikasi()
    {
        $user = User::find(Auth::id());
        $user->unreadNotifications()->update(['read_at' => Carbon::now()]);
        return $this->successResponse();
    }
}
