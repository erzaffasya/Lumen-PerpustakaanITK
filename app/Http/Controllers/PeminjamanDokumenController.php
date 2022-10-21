<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\PeminjamanDokumenResource;
use App\Models\PeminjamanDokumen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if ($this->cek_batasan_dokumen($request->dokumen_id) == False) {
            return $this->errorResponse('Dokumen sudah penuh', 422);
        }

        $Peminjaman = new PeminjamanDokumen(([
            'dokumen_id' => $request->dokumen_id,
            'tgl_peminjaman' => Carbon::now(),
            'tgl_pengembalian' => Carbon::now()->addDays(),
            'user_id' => Auth::user()->id
        ]));
        $Peminjaman->save();

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

        $Peminjaman = PeminjamanDokumen::find($Peminjaman->id)->update([
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'dokumen_id' => $request->dokumen_id,
            'user_id' => $request->user_id,
        ]);

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

    // public function riwayatPeminjamanDokumen()
    // {
    //     $Peminjaman = PeminjamanDokumen::where('tgl_pengembalian', '>', Carbon::now())->get();
    //     $dataPeminjaman = PeminjamanDokumenResource::collection($Peminjaman);

    //     return $this->successResponse($dataPeminjaman);
    // }
}
