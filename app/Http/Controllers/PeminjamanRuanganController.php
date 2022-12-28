<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanRuanganResource;
use App\Http\Resources\UserResource;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\User;
use App\Notifications\NotifRevisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

class PeminjamanRuanganController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->role != "Admin") {
            $Peminjaman = PeminjamanRuangan::where('user_id',  Auth::id())->latest()->get();
        } else {
            $Peminjaman = PeminjamanRuangan::latest()->get();
        }

        if ($request->filter) {
            switch ($request->filter) {
                case 'riwayat':
                    $Peminjaman = PeminjamanRuanganResource::collection($Peminjaman->where('tanggal', '<', Carbon::now()));
                    break;
                case 'berlangsung':
                    $Peminjaman = PeminjamanRuanganResource::collection($Peminjaman->where('tanggal', '>', Carbon::now()));
                    break;
                default:
                    break;
            }
        } else {
            $Peminjaman = PeminjamanRuanganResource::collection($Peminjaman);
        }

        // $PeminjamanRuangan = PeminjamanRuanganResource::collection($Peminjaman);
        return $this->successResponse($Peminjaman);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tanggal' => 'required',
                'waktu_awal' => 'required',
                'waktu_akhir' => 'required',
                'ruangan' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $tanggal = $request->tanggal;
        $waktu_awal = $request->waktu_awal;
        $waktu_akhir = $request->waktu_akhir;
        $ruangan = $request->ruangan;

        if ($waktu_awal > $waktu_akhir) {
            return $this->errorResponse('Waktu akhir tidak sesuai dengan ketentuan', 422);
        }

        if ($waktu_awal < '08:00' || $waktu_akhir > '17:00') {
            return $this->errorResponse('Waktu akhir tidak sesuai dengan ketentuan, batas waktu peminjaman pukul 08:00 sampai 15:00', 422);
        }
        // cek mahasiswa apakah sudah booking/belum
        if (Auth::user()->role == 'Mahasiswa') {
            $cekPeminjaman = PeminjamanRuangan::where('tanggal', '>', Carbon::now()->subDays(1))
                ->where('user_id', Auth::user()->id)->where('status', 'Diterima')->count();
        } else {
            $cekPeminjaman = 0;
        }

        if ($cekPeminjaman < 1) {

            //cek Jadwal tersebut tersedia atau tidak
            $cekRuangan = PeminjamanRuangan::orWhere(function ($query)  use ($waktu_awal, $waktu_akhir, $tanggal, $ruangan) {
                $query
                    ->whereTime('waktu_awal', '>=', $waktu_awal)
                    ->whereTime('waktu_awal', '<=', $waktu_akhir)
                    ->where('status', '=', 'Diterima')
                    ->where('ruangan_id', $ruangan)
                    ->whereDate('tanggal', $tanggal);
            })
                ->orWhere(function ($query)  use ($waktu_awal, $waktu_akhir, $tanggal, $ruangan) {
                    $query
                        ->whereTime('waktu_akhir', '>=', $waktu_awal)
                        ->whereTime('waktu_akhir', '<=', $waktu_akhir)
                        ->where('status', '=', 'Diterima')
                        ->where('ruangan_id', $ruangan)
                        ->whereDate('tanggal', $tanggal);
                })->exists();

            // Jika ruangan tersebut tersedia
            if (!$cekRuangan) {
                $PeminjamanRuangan = new PeminjamanRuangan();
                $PeminjamanRuangan->user_id = Auth::user()->id;
                $PeminjamanRuangan->kode = $this->invoiceNumber();
                $PeminjamanRuangan->tanggal = $request->tanggal;
                $PeminjamanRuangan->ruangan_id = $request->ruangan;
                $PeminjamanRuangan->waktu_awal = $request->waktu_awal;
                $PeminjamanRuangan->waktu_akhir = $request->waktu_akhir;
                $PeminjamanRuangan->keperluan = $request->keperluan;
                if (Auth::user()->role != 'Mahasiswa') {
                    $Ruangan = Ruangan::find($request->ruangan);
                    $PeminjamanRuangan->status = 'Diterima';
                    $dataNotif = [
                        'judul' => 'Peminjaman Ruangan Berhasil',
                        'pesan' => 'Peminjaman Ruangan ' . $Ruangan->nama_ruangan . ' Pada tanggal ' . $PeminjamanRuangan->tanggal . ' Berhasil Dilakukan.',
                    ];
                    // dd('erza');
                    $user = User::find(Auth::user()->id);
                    // dd($user);
                    Notification::send($user, new NotifRevisi($dataNotif));
                    $this->gcalender($request->keperluan . " - Ruangan " . $Ruangan->nama_ruangan . "- Nama " . Auth::user()->name . " " . Auth::user()->nim, $request->tanggal, $request->waktu_awal, $request->waktu_akhir);
                }
                $PeminjamanRuangan->save();
                return  $this->successResponse([
                    'status' => true, 'message' => 'Ruangan Berhasil Ditambahkan',
                    'data' => [
                        'kode' => $PeminjamanRuangan->kode,
                        'tanggal' => $PeminjamanRuangan->tanggal,
                        'waktu_awal' => $PeminjamanRuangan->waktu_awal,
                        'waktu_akhir' => $PeminjamanRuangan->waktu_akhir,
                        'keperluan' => $PeminjamanRuangan->keperluan,
                        'status' => $PeminjamanRuangan->status,
                        'user' => new UserResource(User::find(($PeminjamanRuangan->user_id))),
                        'ruangan' => Ruangan::find($PeminjamanRuangan->ruangan_id),
                    ],
                ]);
            } else {
                return $this->errorResponse(['status' => false, 'message' => 'Kursi Sudah Dibooking'], 422);
            }
        } else {
            return $this->errorResponse(['status' => false, 'message' => 'Anda Sudah Booking Ruangan'], 422);
        }
    }

    public function show($id)
    {
        $PeminjamanRuangan = new PeminjamanRuanganResource(PeminjamanRuangan::find($id));
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
        $PeminjamanRuangan->status = $request->new_status;
        $PeminjamanRuangan->catatan = $request->catatan;
        $PeminjamanRuangan->save();
        $dataNotif = [
            'judul' => 'Perubahan Status Peminjaman Ruangan',
            'pesan' => 'Peminjaman Ruangan ' . $PeminjamanRuangan->nama_ruangan . ' Pada tanggal ' . $PeminjamanRuangan->tanggal . ' Status Berubah Menjadi ' . $PeminjamanRuangan->status,
        ];
        // dd('erza');
        $user = User::find(Auth::user()->id);
        // dd($user);
        Notification::send($user, new NotifRevisi($dataNotif));
        if ($PeminjamanRuangan->status = 'Diterima') {
            $this->gcalender($PeminjamanRuangan->keperluan . " - Ruangan " . $PeminjamanRuangan->Ruangan->nama_ruangan . "- Nama " . $PeminjamanRuangan->User->name . " " . $PeminjamanRuangan->User->nim, $PeminjamanRuangan->tanggal, $PeminjamanRuangan->waktu_awal, $PeminjamanRuangan->waktu_akhir);
        }
        return $this->successResponse(['status' => true, 'message' => 'Peminjaman Ruangan Berhasil Diubah']);
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
        if ($waktu_awal > $waktu_akhir) {
            return $this->errorResponse('Waktu akhir tidak sesuai dengan ketentuan', 422);
        }

        if ($waktu_awal < '08:00' || $waktu_akhir > '17:00') {
            return $this->errorResponse('Waktu akhir tidak sesuai dengan ketentuan, batas waktu peminjaman pukul 08:00 sampai 15:00', 422);
        }

        if ($tanggal != 'undefined' && $waktu_awal != 'undefined' && $waktu_akhir != 'undefined') {
            $cekRuangan = PeminjamanRuangan::where(function ($query)  use ($waktu_awal, $waktu_akhir, $tanggal) {
                $query
                    ->whereTime('waktu_awal', '>', $waktu_awal)
                    ->whereTime('waktu_awal', '<', $waktu_akhir)
                    ->where('status', '=', 'Diterima')
                    ->whereDate('tanggal', $tanggal);
            })
                ->orWhere(function ($query)  use ($waktu_awal, $waktu_akhir, $tanggal) {
                    $query
                        ->whereTime('waktu_akhir', '>', $waktu_awal)
                        ->whereTime('waktu_akhir', '<', $waktu_akhir)
                        ->where('status', '=', 'Diterima')
                        ->whereDate('tanggal', $tanggal);
                })->get();

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

    public function gcalender($namaEvent, $tanggal, $waktuAwal, $waktuAkhir)
    {
        try {
            $event = new Event;
            $event->name = $namaEvent;
            $event->startDateTime = Carbon::parse($tanggal . $waktuAwal);
            $event->endDateTime = Carbon::parse($tanggal . $waktuAkhir);
            $event->save();
        } catch (\Throwable $th) {
            return $this->errorResponse('Google Calendar sedang bermasalah, silahkan coba lagi!', 500);
        }
    }

    public function invoiceNumber()
    {
        $latest = PeminjamanRuangan::latest()->first();
        if (!$latest) {
            return 'BR0001';
        }

        $string = preg_replace("/[^0-9\.]/", '', $latest->kode);

        return 'BR' . sprintf('%04d', (int) $string + 1);
    }

    public function peminjamanRuanganAktif()
    {
        // date('Y-m-d H:i:s', strtotime("$date $time"))
        // $cekPeminjamanRuangan = PeminjamanRuangan::where('tanggal' < Date().now());
    }

    public function peminjamanByRuangan(Request $request, $id)
    {

        if ($request->tanggal == 'undefined' || $request->tanggal == null) {
            $peminjaman = PeminjamanRuangan::where('ruangan_id', $id)->get();
        } else {
            $peminjaman = PeminjamanRuangan::where('ruangan_id', $id)->whereDate('tanggal', $request->tanggal)->get();
        }

        $peminjaman = PeminjamanRuanganResource::collection($peminjaman);

        return $this->successResponse($peminjaman);
    }
}
