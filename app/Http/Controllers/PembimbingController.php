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
        $query = Pembimbing::all();
        if (Auth::user()->role != 'Admin') {
            $query->where('user_id', Auth::user()->id);
        }
        $Pembimbing = PembimbingResource::collection($query);
        return $this->successResponse($Pembimbing);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'dokumen_id' => 'required',
                'user_id' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (Pembimbing::where('user_id', $request->user_id)
            ->where('dokumen_id', $request->dokumen_id)
            ->exists()
        ) {
            return $this->errorResponse('Pembimbing sudah ditambahkan', 422);
        }

        $Pembimbing = new Pembimbing($request->all());
        $Pembimbing->save();

        return $this->successResponse(['status' => true, 'message' => 'Pembimbing Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Pembimbing = new PembimbingResource(Pembimbing::find($id));
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

        return $this->successResponse(['status' => true, 'message' => 'Pembimbing Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Pembimbing = Pembimbing::find($id);
        if (!$Pembimbing) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Pembimbing->delete();
        return $this->successResponse(['status' => true, 'message' => 'Pembimbing Berhasil Dihapus']);
    }

    public function getByDokukumenId($id)
    {
        $Pembimbing = PembimbingResource::collection(Pembimbing::where('dokumen_id', $id)->get());
        return $this->successResponse($Pembimbing);
    }
}
