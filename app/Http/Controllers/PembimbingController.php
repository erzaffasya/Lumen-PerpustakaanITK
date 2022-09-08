<?php

namespace App\Http\Controllers;

use App\Http\Resources\PembimbingResource;
use App\Models\Pembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PembimbingController extends Controller
{
    public function index()
    {
        $Pembimbing = PembimbingResource::collection(Pembimbing::all());
        // dd (Helper::cek_batasan_dokumen(6));


        return $this->successResponse($Pembimbing);
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
     
        $Pembimbing = new Pembimbing(array_merge($request->all(), ['status' => False, 'user_id' => Auth::user()->id]));
        $Pembimbing->save();

        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Pembimbing = Pembimbing::find($id);
        if (!$Pembimbing) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Pembimbing);
    }

    public function update(Request $request, $id)
    {

        $Pembimbing = Pembimbing::find($id);
        if (!$Pembimbing) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Pembimbing = Pembimbing::find($Pembimbing->id)->update([
            'dokumen_id' => $request->dokumen_id,
            'user_id' => $request->user_id,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Pembimbing = Pembimbing::find($id);
        if (!$Pembimbing) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Pembimbing->delete();
        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Berhasil Dihapus']);
    }
}
