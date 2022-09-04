<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KursiBacaResource;
use App\Models\KursiBaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KursiBacaController extends Api
{
    public function index()
    {
        $KursiBaca = KursiBacaResource::collection(KursiBaca::all());
        
        return $this->successResponse($KursiBaca);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode' => 'required',
                'kursi' => 'required',
                'ruangan_baca_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $KursiBaca = new KursiBaca();
        $KursiBaca->kode = $request->kode;
        $KursiBaca->kursi = $request->kursi;
        $KursiBaca->ruangan_baca_id = $request->ruangan_baca_id;
        $KursiBaca->save();

        return $this->successResponse(['status' => true, 'message' => 'KursiBaca Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $KursiBaca = KursiBaca::find($id);
        if (!$KursiBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($KursiBaca);
    }

    public function update(Request $request, $id)
    {

        $KursiBaca = KursiBaca::find($id);
        if (!$KursiBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $KursiBaca = KursiBaca::find($KursiBaca->id)->update([
            'kode' => $request->kode,
            'kursi' => $request->kursi,
            'ruangan_baca_id' => $request->ruangan_baca_id,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'KursiBaca Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $KursiBaca = KursiBaca::find($id);
        if (!$KursiBaca) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $KursiBaca->delete();
        return $this->successResponse(['status' => true, 'message' => 'KursiBaca Berhasil Dihapus']);
    }
}
