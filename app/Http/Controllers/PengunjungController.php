<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PengunjungController extends Controller
{
    public function index()
    {
        // return 'erza';
        // Config::get('app.debug');
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        // $writer->writeFile('Hello World!Erz', 'qrcode.png');
        $qr_image = base64_encode($writer->writeString('erza'));
        return $qr_image;
    }
}
