<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\PeminjamanDokumenResource;
use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use App\Notifications\NotifRevisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PeminjamanDokumenController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $Peminjaman = PeminjamanDokumen::where('user_id',  Auth::id())->get();
        } else {
            $Peminjaman = PeminjamanDokumen::all();
        }

        if ($request->filter) {
            switch ($request->filter) {
                case 'riwayat':
                    $dataPeminjaman = PeminjamanDokumenResource::collection($Peminjaman->where('tgl_pengembalian', '<', Carbon::now()));
                    break;
                case 'berlangsung':
                    $dataPeminjaman = PeminjamanDokumenResource::collection($Peminjaman->where('tgl_pengembalian', '>', Carbon::now()));
                    break;
                default:
                    break;
            }
        } else {
            $dataPeminjaman = PeminjamanDokumenResource::collection($Peminjaman);
        }

        return $this->successResponse($dataPeminjaman);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'dokumen_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (Auth::user()->role != 'Admin') {
            $peminjaman = PeminjamanDokumen::whereDate('tgl_pengembalian', '>', Carbon::now())
                ->where('user_id',Auth::user()->id)
                ->get();

            if ($peminjaman->count() > 5) {
                return $this->errorResponse('Anda sudah meminjaman lebih dari 5 dokumen', 422);
            }
        }

        if ($this->cek_batasan_dokumen($request->dokumen_id) == False) {
            return $this->errorResponse('Dokumen sudah penuh', 422);
        }

        if (PeminjamanDokumen::where('user_id', Auth::id())
            ->where('dokumen_id', $request->dokumen_id)
            ->where('tgl_pengembalian', '>', Carbon::now())
            ->exists()
        ) {
            return $this->errorResponse('Dokumen sudah anda pinjam', 422);
        }

        $Peminjaman = new PeminjamanDokumen(([
            'dokumen_id' => $request->dokumen_id,
            'tgl_peminjaman' => Carbon::now(),
            'tgl_pengembalian' => Carbon::now()->addDays(),
            'user_id' => Auth::user()->id
        ]));
        $Peminjaman->save();

        $Dokumen = Dokumen::find($request->dokumen_id);
        $dataNotif = [
            'judul' => 'Peminjaman Dokumen Berhasil',
            'pesan' => 'Peminjaman Dokumen ' . $Dokumen->judul . ' Berhasil Dilakukan, Masa Berlaku Sampai ' . $Peminjaman->tgl_pengembalian . '.',
        ];
        $user = User::find(Auth::user()->id);
        Notification::send($user, new NotifRevisi($dataNotif));
        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Peminjaman = PeminjamanDokumen::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse(new PeminjamanDokumenResource($Peminjaman));
    }

    public function update(Request $request, $id)
    {

        $Peminjaman = PeminjamanDokumen::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        $Peminjaman->tgl_peminjaman = $request->tgl_peminjaman;
        $Peminjaman->tgl_pengembalian = $request->tgl_pengembalian;
        $Peminjaman->dokumen_id = $request->dokumen_id;
        $Peminjaman->user_id = $request->user_id;
        $Peminjaman->save();

        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Peminjaman = PeminjamanDokumen::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Peminjaman->delete();
        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Dihapus']);
    }

    public function riwayatPeminjaman($id)
    {
        $getRiwayat = PeminjamanDokumenResource::collection(PeminjamanDokumen::where('dokumen_id', $id)->orderBy('created_at', 'DESC')->get());
        return $this->successResponse($getRiwayat);
    }

    public function peminjamanDokumenAktif()
    {
        if (Auth::user()->role != "Admin") {
            $Peminjaman = PeminjamanDokumen::where('user_id',  Auth::id())->get();
        } else {
            $Peminjaman = PeminjamanDokumen::all();
        }
        $cekPeminjamanDokumen = PeminjamanDokumenResource::collection($Peminjaman->where('tgl_pengembalian', '>', Carbon::now()));
        return $this->successResponse($cekPeminjamanDokumen);
    }
}
