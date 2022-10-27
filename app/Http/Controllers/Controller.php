<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Example API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="support@example.com",
     *     name="Support Team"
     *   )
     * )
     */
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     */
    public const ERROR_VALIDATION_CODE = 201;
    public const SUCCESS_MESSAGE_UPDATE = 'SUCCESS TO UPDATE DATA';
    public const ERROR_MESSAGE_WHEN_SAVE = 'ERROR TO UPDATE DATA';
    protected const VIDEO_MIME = ['mp4', 'flv', 'm3u8', 'ts', '3gp', 'mov', 'avi', 'wmv'];
    protected const IMAGE_MIME = ['jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'];

    protected function successResponse($data = null, $message = "success", $code = 200)
    {
        return response()->json([
            'status' => 'success',
            // 'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => $code,
            'data' => null
        ], 200);
    }

    protected function now()
    {
        return date('Y-m-d H:i:s');
    }

    protected function user()
    {
        $user = null;
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
        }
        return $user;
    }

    protected function notifEmail()
    {
        $revisi_data = [
            'judul' => 'erza',
            'pesan' => 'Status Hak Cipta Berubah Menjadi!',
        ];
        $user = User::find(Auth::user()->id);
        Notification::send($user, new NotifRevisi($revisi_data));
    }

    protected function vendor()
    {
        $user = null;
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            if (!$user->is_vendor) {
                return null;
            }
            $user = User::where('id', $user->id)->with('vendor')->first();
        }
        return $user;
    }

    public static function cek_batasan_dokumen($id)
    {
        $peminjaman = PeminjamanDokumen::where('dokumen_id', $id)->where('tgl_pengembalian', '>', Carbon::now())->get();
        if ($peminjaman->count() > 10) {
            return False;
        } else {
            return True;
        }
    }
}
