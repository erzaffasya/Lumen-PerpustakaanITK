<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RuanganBaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuanganBacaController extends Api
{
    public function index()
    {
        $RuanganBaca = RuanganBaca::all();
        return $this->successResponse($RuanganBaca);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode' => 'required',
                'ruangan' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $RuanganBaca = new RuanganBaca();
        $RuanganBaca->kode = $request->kode;
        $RuanganBaca->ruangan = $request->ruangan;
        $RuanganBaca->save();

        return $this->successResponse(['status' => true, 'message' => 'RuanganBaca Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $RuanganBaca = RuanganBaca::find($id);
        if (!$RuanganBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($RuanganBaca);
    }

    public function update(Request $request, $id)
    {

        $RuanganBaca = RuanganBaca::find($id);
        if (!$RuanganBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $RuanganBaca = RuanganBaca::find($RuanganBaca->id)->update([
            'kode' => $request->kode,
            'ruangan' => $request->ruangan,

        ]);

        return $this->successResponse(['status' => true, 'message' => 'RuanganBaca Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $RuanganBaca = RuanganBaca::find($id);
        if (!$RuanganBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $RuanganBaca->delete();
        return $this->successResponse(['status' => true, 'message' => 'RuanganBaca Berhasil Dihapus']);
    }
}
