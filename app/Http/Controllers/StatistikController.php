<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeminjamanDokumenResource;
use App\Http\Resources\PeminjamanDokumenTerpopulerResource;
use App\Http\Resources\PengunjungResource;
use App\Http\Resources\SimpelDokumenResource;
use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\PeminjamanRuangan;
use App\Models\Pengunjung;
use App\Models\Ruangan;
use App\Models\Yudisium;
use App\Models\YudisiumMahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
        $query = Dokumen::all();
        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }
        return $this->successResponse($query->count());
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
        $query = PeminjamanDokumen::all();
        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }
        return $this->successResponse($query->count());
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
        $query = Ruangan::all();
        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }
        return $this->successResponse($query->count());
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
        $query = PeminjamanRuangan::all();
        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }
        return $this->successResponse($query->count());
    }

    public function jumlahYudisium(Request $request)
    {
        $query = Yudisium::all();

        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }

        return $this->successResponse($query->count());
    }

    public function jumlahYudisiumMahasiswa(Request $request)
    {
        $query = YudisiumMahasiswa::all();

        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }

        if ($request->filter) {
            switch ($request->filter) {
                case 'pengajuan':
                    $query = $query
                        ->where('status_final', false);
                    break;
                case 'riwayatPengajuan':
                    $query = $query
                        ->where('status_final', true);
                    break;
                default:
                    break;
            }
        }

        return $this->successResponse($query->count());
    }

    public function peminjamanDokumenPopuler()
    {
        $PeminjamanDokumen = PeminjamanDokumen::select(
            'dokumen.*',
            'kategori.nama_kategori',
            DB::raw('count(peminjaman_dokumen.id) as total_peminjaman')
        )->groupBy('dokumen_id')
            ->join('dokumen', 'peminjaman_dokumen.dokumen_id', '=', 'dokumen.id')
            ->join('kategori', 'kategori.id', '=', 'dokumen.kategori_id')
            ->orderBy('total_peminjaman', 'DESC')
            ->limit(5)
            ->get();
        return $this->successResponse(PeminjamanDokumenTerpopulerResource::collection($PeminjamanDokumen));
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
        $Pengunjung = PengunjungResource::collection(Pengunjung::latest()->take(10)->get());
        return $this->successResponse($Pengunjung);
    }

    public function grafikPerpustakaan()
    {
        if (Auth::user()->role != 'Admin') {
            $Pengunjungs = Pengunjung::select(
                DB::raw('count(id) as total_pengunjung'),
                DB::raw("DATE_FORMAT(created_at,'%m') as bulan")
            )
                ->where('user_id', Auth::user()->id)
                ->groupBy('bulan')
                ->whereYear('created_at', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
            $PeminjamanDokumens = PeminjamanDokumen::select(
                DB::raw('count(id) as total_peminjaman_dokumen'),
                DB::raw("DATE_FORMAT(created_at,'%m') as bulan")
            )
                ->where('user_id', Auth::user()->id)
                ->groupBy('bulan')
                ->whereYear('created_at', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
            $PeminjamanRuangans = PeminjamanRuangan::select(
                DB::raw('count(id) as total_peminjaman_ruangan'),
                DB::raw("DATE_FORMAT(tanggal,'%m') as bulan")
            )
                ->where('user_id', Auth::user()->id)
                ->groupBy('bulan')
                ->whereYear('tanggal', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
        } else {
            $Pengunjungs = Pengunjung::select(
                DB::raw('count(id) as total_pengunjung'),
                DB::raw("DATE_FORMAT(created_at,'%m') as bulan")
            )->groupBy('bulan')->whereYear('created_at', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
            $PeminjamanDokumens = PeminjamanDokumen::select(
                DB::raw('count(id) as total_peminjaman_dokumen'),
                DB::raw("DATE_FORMAT(created_at,'%m') as bulan")
            )->groupBy('bulan')->whereYear('created_at', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
            $PeminjamanRuangans  = PeminjamanRuangan::select(
                DB::raw('count(id) as total_peminjaman_ruangan'),
                DB::raw("DATE_FORMAT(tanggal,'%m') as bulan")
            )->groupBy('bulan')->whereYear('tanggal', date('Y'))
                ->orderBy('bulan', 'ASC')
                ->get();
        }

        $arrayStatistik = [
            'jumlah_pengunjung' => 0,
            'jumlah_peminjaman_dokumen' => 0,
            'jumlah_peminjaman_ruangan' => 0
        ];

        for ($i = 1; $i <= 12; $i++) {
            $arrayStatistik['detail'][$i] = [
                'total_pengunjung' => 0,
                'total_peminjaman_dokumen' => 0,
                'total_peminjaman_ruangan' => 0
            ];

            foreach ($Pengunjungs as $Pengunjung) {
                if ($Pengunjung->bulan == $i) {
                    $arrayStatistik['jumlah_pengunjung'] = $arrayStatistik['jumlah_pengunjung'] + $Pengunjung->total_pengunjung;
                    $arrayStatistik['detail'][$i] = array_merge($arrayStatistik['detail'][$i], ['total_pengunjung' => $Pengunjung->total_pengunjung]);
                }
            }

            foreach ($PeminjamanDokumens as $PeminjamanDokumen) {
                if ($PeminjamanDokumen->bulan == $i) {
                    $arrayStatistik['jumlah_peminjaman_dokumen'] = $arrayStatistik['jumlah_peminjaman_dokumen'] + $PeminjamanDokumen->total_peminjaman_dokumen;
                    $arrayStatistik['detail'][$i] = array_merge($arrayStatistik['detail'][$i], ['total_peminjaman_dokumen' => $PeminjamanDokumen->total_peminjaman_dokumen]);
                }
            }

            foreach ($PeminjamanRuangans as $PeminjamanRuangan) {
                if ($PeminjamanRuangan->bulan == $i) {
                    $arrayStatistik['jumlah_peminjaman_ruangan'] = $arrayStatistik['jumlah_peminjaman_ruangan'] + $PeminjamanRuangan->total_peminjaman_ruangan;
                    $arrayStatistik['detail'][$i] = array_merge($arrayStatistik['detail'][$i], ['total_peminjaman_ruangan' => $PeminjamanRuangan->total_peminjaman_ruangan]);
                }
            }
        }

        return $this->successResponse($arrayStatistik);
    }
}
