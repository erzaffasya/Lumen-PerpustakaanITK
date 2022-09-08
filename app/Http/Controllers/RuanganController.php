<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

class RuanganController extends Controller
{
    public function index()
    {
        $Ruangan = Ruangan::all();
        return $this->successResponse($Ruangan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ruangan' => 'required',
                'deskripsi' => 'required',
                'jumlah_orang' => 'required',
                'lokasi' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $Ruangan = new Ruangan();
        $Ruangan->nama_ruangan = $request->nama_ruangan;
        $Ruangan->deskripsi = $request->deskripsi;
        $Ruangan->jumlah_orang = $request->jumlah_orang;
        $Ruangan->lokasi = $request->lokasi;
        $Ruangan->save();

        return $this->successResponse(['status' => true, 'message' => 'Ruangan Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Ruangan = Ruangan::find($id);
        if (!$Ruangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Ruangan);
    }

    public function update(Request $request, $id)
    {

        $Ruangan = Ruangan::find($id);
        if (!$Ruangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Ruangan = Ruangan::find($Ruangan->id)->update([
            'nama_ruangan' => $request->nama_ruangan,
            'deskripsi' => $request->deskripsi,
            'jumlah_orang' => $request->jumlah_orang,
            'lokasi' => $request->lokasi
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Ruangan Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Ruangan = Ruangan::find($id);
        if (!$Ruangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Ruangan->delete();
        return $this->successResponse(['status' => true, 'message' => 'Ruangan Berhasil Dihapus']);
    }
}
