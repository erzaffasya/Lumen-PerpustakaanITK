<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\PeminjamanResource;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function index()
    {
        $Peminjaman = PeminjamanResource::collection(Peminjaman::all());
        // dd (Helper::cek_batasan_dokumen(6));


        return $this->successResponse($Peminjaman);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tgl_peminjaman' => 'required',
                'tgl_pengembalian' => 'required',
                'dokumen_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($this->cek_batasan_dokumen($request->dokumen_id) == False) {
            return $this->errorResponse('Dokumen sudah penuh', 422);
        }
     
        $Peminjaman = new Peminjaman(array_merge($request->all(), ['status' => False, 'user_id' => Auth::user()->id]));
        $Peminjaman->save();

        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Peminjaman = Peminjaman::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse(new PeminjamanResource($Peminjaman));
    }

    public function update(Request $request, $id)
    {

        $Peminjaman = Peminjaman::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Peminjaman = Peminjaman::find($Peminjaman->id)->update([
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'status' => $request->status,
            'dokumen_id' => $request->dokumen_id,
            'user_id' => $request->user_id,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Peminjaman = Peminjaman::find($id);
        if (!$Peminjaman) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Peminjaman->delete();
        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Dihapus']);
    }
}
