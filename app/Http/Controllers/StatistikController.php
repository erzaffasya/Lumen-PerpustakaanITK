<?php

namespace App\Http\Controllers;

use App\Http\Resources\PengunjungResource;
use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\PeminjamanRuangan;
use App\Models\Pengunjung;
use App\Models\Ruangan;
use Shetabit\Visitor\Models\Visit;

class StatistikController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/jumlah-dokumen",
     *  tags={"Statistik"},
     *  summary="Get jumlah-dokumen",
     *  @OA\Response(response=200, description="Get jumlah-dokumen"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function jumlahDokumen()
    {
        $Dokumen = Dokumen::count();
        return $this->successResponse($Dokumen);
    }
    /**
     * @OA\Get(
     *  path="/api/jumlah-peminjaman-dokumen",
     *  tags={"Statistik"},
     *  summary="Get jumlah-peminjaman-dokumen",
     *  @OA\Response(response=200, description="Get jumlah-peminjaman-dokumen"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function jumlahPeminjamanDokumen()
    {
        $PeminjamanDokumen = PeminjamanDokumen::count();
        return $this->successResponse($PeminjamanDokumen);
    }

    /**
     * @OA\Get(
     *  path="/api/jumlah-ruangan",
     *  tags={"Statistik"},
     *  summary="Get jumlah-ruangan",
     *  @OA\Response(response=200, description="Get jumlah-ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function jumlahRuangan()
    {
        $Ruangan = Ruangan::count();
        return $this->successResponse($Ruangan);
    }

    /**
     * @OA\Get(
     *  path="/api/jumlah-peminjaman-ruangan",
     *  tags={"Statistik"},
     *  summary="Get jumlah-peminjaman-ruangan",
     *  @OA\Response(response=200, description="Get jumlah-peminjaman-ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
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

    /**
     * @OA\Get(
     *  path="/api/jumlah-pengunjung",
     *  tags={"Statistik"},
     *  summary="Get jumlah-pengunjung",
     *  @OA\Response(response=200, description="Get jumlah-pengunjung"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function jumlahPengunjung()
    {
        $Pengunjung = Pengunjung::select('pengunjung.user_id', 'users.name')->selectRaw('count(*) as jumlah_kunjungan')
            ->join('users', 'pengunjung.user_id', '=', 'users.id')->GroupBy('pengunjung.user_id', 'users.name')
            ->orderBy('jumlah_kunjungan', 'DESC')->limit(10)->get();

        return $this->successResponse($Pengunjung);
    }
    public function pengunjungTerbaru()
    {
        $Pengunjung = PengunjungResource::collection(Pengunjung::limit(10)->latest()->get());
        return $this->successResponse($Pengunjung);
    }
}
