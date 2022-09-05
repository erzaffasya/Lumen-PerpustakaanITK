<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KursiBaca;
use App\Models\PeminjamanRuangan;
use App\Models\RuanganBaca;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PeminjamanRuanganController extends Controller
{
    public function index()
    {
        $PeminjamanRuangan = PeminjamanRuangan::all();
        return $this->successResponse($PeminjamanRuangan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kursi_baca_id' => 'required',
                'tanggal_peminjaman' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $cekRuangan = PeminjamanRuangan::where('kursi_baca_id', $request->kursi_baca_id)
            ->where('tanggal_peminjaman', $request->tanggal_peminjaman)
            ->first();
        $cekPeminjaman = PeminjamanRuangan::where('tanggal_peminjaman', '>', Carbon::now()->subDays(1))
            ->where('user_id', Auth::user()->id)->count();
        // return $cekPeminjaman;
        if ($cekRuangan == null) {
            if ($cekPeminjaman < 1) {
                $PeminjamanRuangan = new PeminjamanRuangan();
                $PeminjamanRuangan->user_id = Auth::user()->id;
                $PeminjamanRuangan->kursi_baca_id = $request->kursi_baca_id;
                $PeminjamanRuangan->tanggal_peminjaman = $request->tanggal_peminjaman;
                $PeminjamanRuangan->save();
                return $this->successResponse(['status' => true, 'message' => 'PeminjamanRuangan Berhasil Ditambahkan']);
            } else {
                return $this->errorResponse(['status' => false, 'message' => 'Anda Sudah Booking Ruangan'], 422);
            }
        } else {
            return $this->errorResponse(['status' => false, 'message' => 'Kursi Sudah Dibooking'], 422);
        }
    }

    public function show($id)
    {
        $PeminjamanRuangan = PeminjamanRuangan::find($id);
        if (!$PeminjamanRuangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($PeminjamanRuangan);
    }

    public function update(Request $request, $id)
    {

        $PeminjamanRuangan = PeminjamanRuangan::find($id);
        if (!$PeminjamanRuangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $PeminjamanRuangan = PeminjamanRuangan::find($PeminjamanRuangan->id)->update([
            'user_id' => $request->user_id,
            'kursi_baca_id' => $request->kursi_baca_id,
            'kursi_baca_id_baca_id' => $request->tanggal_peminjaman,

        ]);

        return $this->successResponse(['status' => true, 'message' => 'PeminjamanRuangan Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $PeminjamanRuangan = PeminjamanRuangan::find($id);
        if (!$PeminjamanRuangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $PeminjamanRuangan->delete();
        return $this->successResponse(['status' => true, 'message' => 'PeminjamanRuangan Berhasil Dihapus']);
    }

    public function RuanganKosong($ruang, $tanggal)
    {
        $dataKursiBaca = KursiBaca::select('kursi_baca.*', 'ruangan_baca.ruangan')
            ->join('ruangan_baca', 'kursi_baca.ruangan_baca_id', 'ruangan_baca.id')
            ->where('kursi_baca.ruangan_baca_id', '=', $ruang)
            ->get();

        if (count($dataKursiBaca) == 0) {
            return $this->errorResponse('Kursi tidak tersedia', 422);
        }

        if ($tanggal == 'undefined') {
            return response()->json(['error' => 'Data Tidak Lengkap'], 422);
        }

        foreach ($dataKursiBaca as $item) {
            $cekKursi = PeminjamanRuangan::where('kursi_baca_id', '=', $item->id)->where('tanggal_peminjaman', '=', $tanggal)->first();

            // True = Tersedia, False = Tidak Tersedia
            if ($cekKursi == null) {
                $data = True;
            } else {
                $data = False;
            }

            $Ruangan[] = array_merge([
                'id' => $item->id,
                'nama_kursi' => $item->kursi,
                'nama_ruangan' => $item->ruangan,
                'status_kursi' => $item->status_kursi
            ], ['status_kursi' => $data]);
        }
        return $this->successResponse($Ruangan);
    }
}
