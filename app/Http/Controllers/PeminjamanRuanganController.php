<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KursiBaca;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\RuanganBaca;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

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
                'ruangan_id' => 'required',
                'tanggal' => 'required',
                'waktu_awal' => 'required',
                'waktu_akhir' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $cekPeminjaman = PeminjamanRuangan::where('tanggal', '>', Carbon::now()->subDays(1))
            ->where('user_id', Auth::user()->id)->where('status', 'Diterima')->count();

        if ($cekPeminjaman < 1) {
            $cekRuangan = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
                ->whereDate('tanggal', $request->tanggal)
                ->whereTime('waktu_awal', '>=', $request->waktu_awal)
                ->whereTime('waktu_akhir', '<=', $request->waktu_akhir)
                ->exists();

            if (!$cekRuangan) {
                $PeminjamanRuangan = new PeminjamanRuangan();
                $PeminjamanRuangan->user_id = Auth::user()->id;
                $PeminjamanRuangan->tanggal = $request->tanggal;
                $PeminjamanRuangan->ruangan_id = $request->ruangan_id;
                $PeminjamanRuangan->waktu_awal = $request->waktu_awal;
                $PeminjamanRuangan->waktu_akhir = $request->waktu_akhir;
                $PeminjamanRuangan->keperluan = $request->keperluan;
                $PeminjamanRuangan->status = 'Menunggu';
                $PeminjamanRuangan->save();
                return $this->successResponse(['status' => true, 'message' => 'Peminjaman Ruangan Berhasil Ditambahkan']);
            } else {
                return $this->errorResponse(['status' => false, 'message' => 'Kursi Sudah Dibooking'], 422);
            }
        } else {
            return $this->errorResponse(['status' => false, 'message' => 'Anda Sudah Booking Ruangan'], 422);
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

    public function RuanganKosong($tanggal, $waktu_awal, $waktu_akhir)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'tanggal' => $tanggal,
                'waktu_awal' => $waktu_awal,
                'waktu_akhir' => $waktu_akhir,
            ],
            [
                'tanggal' => ['required'],
                'waktu_awal' => ['required'],
                'waktu_akhir' => ['required'],
            ],
        );


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($tanggal != 'undefined' && $waktu_awal != 'undefined' && $waktu_akhir != 'undefined') {
            $cekRuangan = PeminjamanRuangan::whereDate('tanggal', $tanggal)
                ->whereTime('waktu_awal', '>=', $waktu_awal)
                ->whereTime('waktu_akhir', '<=', $waktu_akhir)
                ->get();

            $getRuangan = Ruangan::all();
            foreach ($getRuangan as $dataRuangan) {
                $data = True;
                foreach ($cekRuangan as $item) {
                    // True = Tersedia, False = Tidak Tersedia
                    if ($dataRuangan->id == $item->ruangan_id) {
                        $data = False;
                    }
                }

                $Ruangan[] = array_merge([
                    'id' => $dataRuangan->id,
                    'nama_ruangan' => $dataRuangan->nama_ruangan,
                    'deskripsi' => $dataRuangan->deskripsi,
                    'jumlah_orang' => $dataRuangan->jumlah_orang,
                    'lokasi' => $dataRuangan->lokasi
                ], ['status_kursi' => $data]);
            }

            return $this->successResponse($Ruangan);
        } else {
            return $this->errorResponse('Data Tidak Lengkap', 422);
        }
    }

    public function gcalender()
    {
        $event =  Event::get();
        return $event;
        // $event = new Event;

        // $event->name = 'A new event';
        // $event->startDateTime = Carbon::now();
        // $event->endDateTime = Carbon::now()->addHour();

        // $event->save();
    }
}
