<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\PeminjamanRuangan;
use App\Models\Pengunjung;
use App\Models\Ruangan;
use Shetabit\Visitor\Models\Visit;

class StatistikController extends Controller
{
    public function jumlahDokumen()
    {
        $Dokumen = Dokumen::count();
        return $this->successResponse($Dokumen);
    }
    public function jumlahPeminjamanDokumen()
    {
        $PeminjamanDokumen = PeminjamanDokumen::count();
        return $this->successResponse($PeminjamanDokumen);
    }
    public function jumlahRuangan()
    {
        $Ruangan = Ruangan::count();
        return $this->successResponse($Ruangan);
    }
    public function jumlahPeminjamanRuangan()
    {
        $PeminjamanRuangan = PeminjamanRuangan::count();
        return $this->successResponse($PeminjamanRuangan);
    }
    public function peminjamanDokumenPopuler()
    {
    }
    public function peminjamanRuanganPopuler()
    {
    }
    public function jumlahPengunjung()
    {
        $Pengunjung = Pengunjung::select('pengunjung.user_id', 'users.name')->selectRaw('count(*) as jumlah_kunjungan')
            ->join('users', 'pengunjung.user_id', '=', 'users.id')->GroupBy('pengunjung.user_id', 'users.name')
            ->orderBy('jumlah_kunjungan', 'DESC')->limit(10)->get();
            
        return $this->successResponse($Pengunjung);
    }
    public function pengunjungTerbaru()
    {
    }
}
