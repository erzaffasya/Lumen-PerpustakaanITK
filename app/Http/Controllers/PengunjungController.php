<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengunjungResource;
use App\Models\Pengunjung;
use App\Models\User;
use Carbon\Carbon;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengunjungController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/pengunjung",
     *  tags={"Pengunjung Perpustakaan"},
     *  summary="Get pengunjung",
     *  @OA\Response(response=200, description="Get pengunjung"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        $data = PengunjungResource::collection(Pengunjung::all());
        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     *  path="/api/qrcode",
     *  tags={"Pengunjung Perpustakaan"},
     *  summary="Get qrcode",
     *  @OA\Response(response=200, description="Get qrcode"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function qrcode()
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        $url = url("/api/checkin-pengunjung");
        $writer = new Writer($renderer);
        $qr_image = base64_encode($writer->writeString($url));

        return $this->successResponse(['qrcode_pengunjung' => $qr_image]);
    }

    /**
     * @OA\Post(
     *  path="/api/pengunjung",
     *  tags={"Pengunjung Perpustakaan"},
     *  summary="Post pengunjung",
     *  @OA\Response(response=200, description="Post pengunjung"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function store()
    {
        $cekPengunjung = Pengunjung::where('user_id', Auth::id())
            ->whereBetween('created_at', [Carbon::now()->subMinutes(120), Carbon::now()])
            ->first();

        if ($cekPengunjung) {
            return $this->errorResponse('Anda Sudah Checkin', 422);
        } else {
            $Pengunjung = new Pengunjung();
            $Pengunjung->user_id = Auth::id();
            $Pengunjung->save();
            return $this->successResponse(['status' => true, 'message' => 'Checkin Berhasil Dilakukan']);
        }
    }

    public function tambahPengunjung(Request $request)
    {
        // dd(request()->all());
        $cekData = User::where('nim', $request->nim)->first();
        if (!$cekData) {
            return $this->errorResponse('Data Tidak Ditemukan', 422);
        }
        // dd($cekData);
        $cekPengunjung = Pengunjung::where('user_id', $cekData->id)
            ->whereBetween('created_at', [Carbon::now()->subMinutes(120), Carbon::now()])
            ->first();
        // dd($cekPengunjung);
        if ($cekPengunjung) {
            return $this->errorResponse('Anda Sudah Checkin', 422);
        } else {
            $Pengunjung = new Pengunjung();
            $Pengunjung->user_id = $cekData->id;
            $Pengunjung->save();
            return $this->successResponse(['status' => true, 'message' => 'Checkin Berhasil Dilakukan']);
        }
    }

    public function destroy($id)
    {
        $Pengunjung = Pengunjung::find($id);
        if (!$Pengunjung) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Pengunjung->delete();
        return $this->successResponse(['status' => true, 'message' => 'Pengunjung Berhasil Dihapus']);
    }
}
