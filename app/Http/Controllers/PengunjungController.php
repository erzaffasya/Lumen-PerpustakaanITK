<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PengunjungController extends Controller
{
    public function index()
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        $url = url("/checkin-pengunjung");
        $writer = new Writer($renderer);
        $qr_image = base64_encode($writer->writeString($url));

        // return url("/checkin-pengunjung");
        return $qr_image;
        return $this->successResponse(['qrcode_pengunjung' => $qr_image]);
    }

    public function store()
    {
        $cekPengunjung = Pengunjung::whereBetween('created_at', [Carbon::now()->subMinutes(120), Carbon::now()])
            ->first();

        if ($cekPengunjung) {
            return $this->errorResponse('Anda Sudah Checkin', 422);
        } else {
            Pengunjung::created([
                'user_id' => Auth::user()->id
            ]);
            return $this->successResponse(['status' => true, 'message' => 'Checkin Berhasil Dilakukan']);
        }
    }
}
