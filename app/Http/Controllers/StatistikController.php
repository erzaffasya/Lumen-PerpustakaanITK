<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeminjamanDokumenResource;
use App\Http\Resources\PengunjungResource;
use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\PeminjamanRuangan;
use App\Models\Pengunjung;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
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
        $PeminjamanDokumen = PeminjamanDokumen::select(
            'dokumen.id',
            'dokumen.judul',
            'dokumen.nama_pengarang',
            'dokumen.tahun_terbit',
            'kategori.nama_kategori',
            DB::raw('count(peminjaman_dokumen.id) as total_peminjaman')
        )->groupBy('dokumen_id')
            ->join('dokumen', 'peminjaman_dokumen.dokumen_id', '=', 'dokumen.id')
            ->join('kategori', 'kategori.id', '=', 'dokumen.kategori_id')
            ->orderBy('total_peminjaman', 'DESC')
            ->limit(5)
            ->get();
        return $this->successResponse($PeminjamanDokumen);
    }
    public function peminjamanRuanganPopuler()
    {
        $PeminjamanRuangan = PeminjamanRuangan::select(
            'ruangan.id',
            'ruangan.nama_ruangan',
            'ruangan.deskripsi',
            'ruangan.jumlah_orang',
            'ruangan.lokasi',
            DB::raw('count(peminjaman_ruangan.id) as total_peminjaman')
        )->groupBy('ruangan_id')
            ->join('ruangan', 'peminjaman_ruangan.ruangan_id', '=', 'ruangan.id')
            ->orderBy('total_peminjaman', 'DESC')
            ->limit(5)
            ->get();
        return $this->successResponse($PeminjamanRuangan);
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

    public function grafikPengunjung()
    {
        $Pengunjung = Pengunjung::select(
            DB::raw('count(id) as total_pengunjung'),
            DB::raw("DATE_FORMAT(created_at,'%m') as bulan")
        )->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get();


        for ($i = 1; $i <= 12; $i++) {
            foreach ($Pengunjung as $item) {
            }
            if ((int)$item->bulan == $i) {
                $data[] = $item->total_pengunjung;
            } else {
                $data[] = 0;
            }
        }

        return $this->successResponse($data);
    }
}
