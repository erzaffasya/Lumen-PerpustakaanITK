<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\DokumenResource;
use App\Http\Resources\SimpelDokumenResource;
use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\Pembimbing;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use App\Notifications\NotifRevisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::select('dokumen.*', 'kategori.nama_kategori')
            ->where('nama_kategori', 'LIKE', "%{$request->kategori}%")
            ->join('kategori', 'kategori.id', 'dokumen.kategori_id')
            ->get();

        if ($request->status) {
            if ($request->status != 'Riwayat') {
                $query = $query->where('status', $request->status);
            } else {
                $query = $query->where('status', '!=', 'Revisi')->where('status', '!=', 'Diproses');
            }
        }

        if (Auth::user()->role != 'Admin') {
            $query = $query->where('user_id', Auth::user()->id);
        }

        $Dokumen = DokumenResource::collection($query);
        return $this->successResponse($Dokumen);
    }

    public function downloadFile($id, $data)
    {
        if (Auth::user()->role != 'Admin' && Auth::user()->role != 'Dosen') {
            return $this->errorResponse('Anda Tidak Memiliki Akses Untuk Mendownload Berkas', 403);
        }

        $dokumen = Dokumen::find($id);
        switch ($data) {
            case 'cover':
                $file = $dokumen->cover;
                break;
            case 'lembar_pengesahan':
                $file = $dokumen->lembar_pengesahan;
                break;
            case 'kata_pengantar':
                $file = $dokumen->kata_pengantar;
                break;
            case 'ringkasan':
                $file = $dokumen->ringkasan;
                break;
            case 'daftar_isi':
                $file = $dokumen->daftar_isi;
                break;
            case 'daftar_gambar':
                $file = $dokumen->daftar_gambar;
                break;
            case 'daftar_tabel':
                $file = $dokumen->daftar_tabel;
                break;
            case 'daftar_notasi':
                $file = $dokumen->daftar_notasi;
                break;
            case 'abstract_en':
                $file = $dokumen->abstract_en;
                break;
            case 'abstract_id':
                $file = $dokumen->abstract_id;
                break;
            case 'bab1':
                $file = $dokumen->bab1;
                break;
            case 'bab2':
                $file = $dokumen->bab2;
                break;
            case 'bab3':
                $file = $dokumen->bab3;
                break;
            case 'bab4':
                $file = $dokumen->bab4;
                break;
            case 'kesimpulan':
                $file = $dokumen->kesimpulan;
                break;
            case 'daftar_pustaka':
                $file = $dokumen->daftar_pustaka;
                break;
            case 'lampiran':
                $file = $dokumen->lampiran;
                break;
            case 'paper':
                $file = $dokumen->paper;
                break;
            case 'lembar_persetujuan':
                $file = $dokumen->lembar_persetujuan;
                break;
            case 'full_dokumen':
                $file = $dokumen->full_dokumen;
                break;
            case 'lembar_pengesahan':
                $file = $dokumen->lembar_pengesahan;
                break;
            default:
        }

        $myFile = public_path($file);
        $headers = ['Content-Type: application/pdf'];
        $newName = $dokumen->judul . '-' . $data . '.pdf';
        return response()->download($myFile, $newName, $headers);
    }

    public function cekAksesDokumen($id, $data)
    {
        $dokumen = Dokumen::find($id);
        // dd($id);
        //cek hak akses
        $url = url('api/showDokumen/' . $id . '/' . $data);
        if (Auth::user()->role != 'Admin') {

            if (Dokumen::where('id', $id)
                ->where('user_id', Auth::user()->id)
                ->exists()
            ) {
                return $this->successResponse(['link' => $url]);
            }

            $cekStatus = PeminjamanDokumen::where('user_id', Auth::user()->id)
                ->where('dokumen_id', $id)
                // ->where('user_id','!=',Auth::user()->id)
                ->where('tgl_pengembalian', '>', Carbon::now())
                ->exists();

            if ($cekStatus) {
                return $this->successResponse(['link' => $url]);
            } else {
                return $this->errorResponse('Anda Tidak Memiliki Akses', 403);
            }
        }
        return $this->successResponse(['link' => $url]);
    }
    public function showfile($id, $data)
    {
        $dokumen = Dokumen::find($id);

        switch ($data) {
            case 'cover':
                $file = $dokumen->cover;
                break;
            case 'lembar_pengesahan':
                $file = $dokumen->lembar_pengesahan;
                break;
            case 'kata_pengantar':
                $file = $dokumen->kata_pengantar;
                break;
            case 'ringkasan':
                $file = $dokumen->ringkasan;
                break;
            case 'daftar_isi':
                $file = $dokumen->daftar_isi;
                break;
            case 'daftar_gambar':
                $file = $dokumen->daftar_gambar;
                break;
            case 'daftar_tabel':
                $file = $dokumen->daftar_tabel;
                break;
            case 'daftar_notasi':
                $file = $dokumen->daftar_notasi;
                break;
            case 'abstract_en':
                $file = $dokumen->abstract_en;
                break;
            case 'abstract_id':
                $file = $dokumen->abstract_id;
                break;
            case 'bab1':
                $file = $dokumen->bab1;
                break;
            case 'bab2':
                $file = $dokumen->bab2;
                break;
            case 'bab3':
                $file = $dokumen->bab3;
                break;
            case 'bab4':
                $file = $dokumen->bab4;
                break;
            case 'kesimpulan':
                $file = $dokumen->kesimpulan;
                break;
            case 'daftar_pustaka':
                $file = $dokumen->daftar_pustaka;
                break;
            case 'lampiran':
                $file = $dokumen->lampiran;
                break;
            case 'paper':
                $file = $dokumen->paper;
                break;
            case 'lembar_persetujuan':
                $file = $dokumen->lembar_persetujuan;
                break;
            case 'full_dokumen':
                $file = $dokumen->full_dokumen;
                break;
            case 'lembar_pengesahan':
                $file = $dokumen->lembar_pengesahan;
                break;
            default:
        }

        $file = File::get(public_path($file));
        $response = Response()->make($file, 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;
    }

    public function view_dokumen($id, $file_name)
    {
        // Check if file exists in app/storage/file folder
        // return $id;
        $file_path = Storage::url("documents/" . $id . "/" . $file_name);
        // dd($file_path, $id, $file_name);
        // return $file_path;
        // if (file_exists($file_path)) {
        return Dokumen::make(file_get_contents($file_path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file_name . '"'
        ]);
        // } else {
        //     // Error
        //     exit('Requested file does not exist on our server!');
        // }
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'kategori_id' => 'required',
                'gambar_dokumen' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
                'cover' => 'mimes:pdf|max:10000',
                'lembar_pengesahan' => 'mimes:pdf|max:10000',
                'kata_pengantar' => 'mimes:pdf|max:10000',
                'ringkasan' => 'mimes:pdf|max:10000',
                'daftar_isi' => 'mimes:pdf|max:10000',
                'daftar_gambar' => 'mimes:pdf|max:10000',
                'daftar_tabel' => 'mimes:pdf|max:10000',
                'daftar_notasi' => 'mimes:pdf|max:10000',
                'abstract_en' => 'mimes:pdf|max:10000',
                'abstract_id' => 'mimes:pdf|max:10000',
                'bab1' => 'mimes:pdf|max:10000',
                'bab2' => 'mimes:pdf|max:10000',
                'bab3' => 'mimes:pdf|max:10000',
                'bab4' => 'mimes:pdf|max:10000',
                'lampiran' => 'mimes:pdf|max:10000',
                'kesimpulan' => 'mimes:pdf|max:10000',
                'daftar_pustaka' => 'mimes:pdf|max:10000',
                'paper' => 'mimes:pdf|max:10000',
                'lembar_persetujuan' => 'mimes:pdf|max:10000',
                'full_dokumen' => 'mimes:pdf|max:1000000',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }


        $Dokumen = new Dokumen();
        $Dokumen->judul = $request->judul;
        $Dokumen->kategori_id = $request->kategori_id;
        $Dokumen->tahun_terbit = $request->tahun_terbit;
        $Dokumen->nama_pengarang = $request->nama_pengarang;
        $Dokumen->deskripsi = $request->deskripsi;
        $Dokumen->penerbit = $request->penerbit;
        $Dokumen->user_id = Auth::user()->id;



        if ($request->gambar_dokumen != null) {
            $file_ext = $request->gambar_dokumen->extension();
            $file_name = 'gambar_dokumen_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $gambar_dokumen = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('gambar_dokumen')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->gambar_dokumen = $gambar_dokumen;
        }
        // Cover 
        if ($request->cover != null) {
            $file_ext = $request->cover->extension();
            $file_name = 'cover_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $cover = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('cover')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->cover = $cover;
        }
        if ($request->lembar_pengesahan != null) {
            $file_ext = $request->lembar_pengesahan->extension();
            $file_name = 'lembar_pengesahan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lembar_pengesahan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('lembar_pengesahan')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->lembar_pengesahan = $lembar_pengesahan;
        }
        if ($request->kata_pengantar != null) {
            $file_ext = $request->kata_pengantar->extension();
            $file_name = 'kata_pengantar_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $kata_pengantar = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('kata_pengantar')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->kata_pengantar = $kata_pengantar;
        }
        if ($request->ringkasan != null) {
            $file_ext = $request->ringkasan->extension();
            $file_name = 'ringkasan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $ringkasan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('ringkasan')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->ringkasan = $ringkasan;
        }
        if ($request->daftar_isi != null) {
            $file_ext = $request->daftar_isi->extension();
            $file_name = 'daftar_isi_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_isi = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_isi')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_isi = $daftar_isi;
        }
        if ($request->daftar_gambar != null) {
            $file_ext = $request->daftar_gambar->extension();
            $file_name = 'daftar_gambar_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_gambar = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_gambar')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_gambar = $daftar_gambar;
        }
        if ($request->daftar_tabel != null) {
            $file_ext = $request->daftar_tabel->extension();
            $file_name = 'daftar_tabel_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_tabel = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_tabel')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_tabel = $daftar_tabel;
        }
        if ($request->daftar_notasi != null) {
            $file_ext = $request->daftar_notasi->extension();
            $file_name = 'daftar_notasi_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_notasi = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_notasi')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_notasi = $daftar_notasi;
        }
        // // abstract_en 
        if ($request->abstract_en != null) {
            $file_ext = $request->abstract_en->extension();
            $file_name = 'abstract_en_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $abstract_en = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->abstract_en->move("storage/documents/$Dokumen->user_id",  $file_name);
            $Dokumen->abstract_en = $abstract_en;
        }

        // // abstract_id 
        if ($request->abstract_id != null) {
            $file_ext = $request->abstract_id->extension();
            $file_name = 'abstract_id_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $abstract_id = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->abstract_id->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->abstract_id = $abstract_id;
        }

        // bab1 
        if ($request->bab1 != null) {
            $file_ext = $request->bab1->extension();
            $file_name = 'bab1_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab1 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab1')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab1 = $bab1;
        }

        // bab2 
        if ($request->bab2 != null) {
            $file_ext = $request->bab2->extension();
            $file_name = 'bab2_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab2 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab2')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab2 = $bab2;
        }

        // bab3 
        if ($request->bab3 != null) {
            $file_ext = $request->bab3->extension();
            $file_name = 'bab3_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab3 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab3')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab3 = $bab3;
        }

        // bab4 
        if ($request->bab4 != null) {
            $file_ext = $request->bab4->extension();
            $file_name = 'bab4_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab4 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab4')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab4 = $bab4;
        }
        if ($request->lampiran != null) {
            $file_ext = $request->lampiran->extension();
            $file_name = 'lampiran_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lampiran = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('lampiran')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->lampiran = $lampiran;
        }
        // kesimpulan 
        if ($request->kesimpulan != null) {
            $file_ext = $request->kesimpulan->extension();
            $file_name = 'kesimpulan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $kesimpulan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->kesimpulan->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->kesimpulan = $kesimpulan;
        }

        // daftar_pustaka 
        if ($request->daftar_pustaka != null) {
            $file_ext = $request->daftar_pustaka->extension();
            $file_name = 'daftar_pustaka_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_pustaka = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->daftar_pustaka->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_pustaka = $daftar_pustaka;
        }

        // paper 
        if ($request->paper != null) {
            $file_ext = $request->paper->extension();
            $file_name = 'paper_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $paper = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->paper->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->paper = $paper;
        }

        // lembar_persetujuan 
        if ($request->lembar_persetujuan != null) {
            $file_ext = $request->lembar_persetujuan->extension();
            $file_name = 'lembar_persetujuan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lembar_persetujuan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->lembar_persetujuan->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->lembar_persetujuan = $lembar_persetujuan;
        }

        // full_dokumen 
        if ($request->full_dokumen != null) {
            $file_ext = $request->full_dokumen->extension();
            $file_name = 'full_dokumen_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $full_dokumen = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('full_dokumen')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->full_dokumen = $full_dokumen;
        }
        $Dokumen->save();
        if ($request->pembimbing_1) {
            Pembimbing::create([
                'dokumen_id' => $Dokumen->id,
                'user_id' => $request->pembimbing_1
            ]);
        }
        if ($request->pembimbing_2) {
            Pembimbing::create([
                'dokumen_id' => $Dokumen->id,
                'user_id' => $request->pembimbing_2
            ]);
        }
        return $this->successResponse(['status' => true, 'message' => 'Dokumen Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $showDokumen = Dokumen::findOrFail($id);
        $Dokumen = new DokumenResource($showDokumen);
        if (!$Dokumen) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $user = User::find(Auth::id());
        $showDokumen->createVisitLog($user);

        return $this->successResponse($Dokumen);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'kategori_id' => 'required',
                'gambar_dokumen' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
                'cover' => 'mimes:pdf|max:10000',
                'lembar_pengesahan' => 'mimes:pdf|max:10000',
                'kata_pengantar' => 'mimes:pdf|max:10000',
                'ringkasan' => 'mimes:pdf|max:10000',
                'daftar_isi' => 'mimes:pdf|max:10000',
                'daftar_gambar' => 'mimes:pdf|max:10000',
                'daftar_tabel' => 'mimes:pdf|max:10000',
                'daftar_notasi' => 'mimes:pdf|max:10000',
                'abstract_en' => 'mimes:pdf|max:10000',
                'abstract_id' => 'mimes:pdf|max:10000',
                'bab1' => 'mimes:pdf|max:10000',
                'bab2' => 'mimes:pdf|max:10000',
                'bab3' => 'mimes:pdf|max:10000',
                'bab4' => 'mimes:pdf|max:10000',
                'lampiran' => 'mimes:pdf|max:10000',
                'kesimpulan' => 'mimes:pdf|max:10000',
                'daftar_pustaka' => 'mimes:pdf|max:10000',
                'paper' => 'mimes:pdf|max:10000',
                'lembar_persetujuan' => 'mimes:pdf|max:10000',
                'full_dokumen' => 'mimes:pdf|max:1000000',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        
        $Dokumen = Dokumen::find($id);
        if (!$Dokumen) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        // return ($request->all());
        $Dokumen->judul = $request->judul;
        $Dokumen->kategori_id = $request->kategori_id;
        $Dokumen->tahun_terbit = $request->tahun_terbit;
        $Dokumen->nama_pengarang = $request->nama_pengarang;
        $Dokumen->deskripsi = $request->deskripsi;
        $Dokumen->penerbit = $request->penerbit;
        $Dokumen->data_tambahan = $request->data_tambahan;
        if ($Dokumen->status == 'Revisi') {
            $Dokumen->status = 'Diproses';
        }
        if ($Dokumen->user_id != null) {
            $Dokumen->user_id = $Dokumen->user_id;
        }

        // Gambar Dokumen 
        if ($request->gambar_dokumen != null && $request->gambar_dokumen != 'null') {
            $file_ext = $request->gambar_dokumen->extension();
            $file_name = 'gambar_dokumen_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $gambar_dokumen = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('gambar_dokumen')->move("storage/documents/$Dokumen->user_id", $gambar_dokumen);
            $Dokumen->gambar_dokumen = $gambar_dokumen;
        }
        // Cover 
        if ($request->cover != null && $request->cover != 'null') {
            $file_ext = $request->cover->extension();
            $file_name = 'cover_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $cover = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('cover')->move("storage/documents/$Dokumen->user_id", $cover);
            $Dokumen->cover = $cover;
        }
        // lembar_pengesahan 
        if ($request->lembar_pengesahan != null && $request->lembar_pengesahan != 'null') {
            $file_ext = $request->lembar_pengesahan->extension();
            $file_name = 'lembar_pengesahan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lembar_pengesahan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('lembar_pengesahan')->move("storage/documents/$Dokumen->user_id", $lembar_pengesahan);
            $Dokumen->lembar_pengesahan = $lembar_pengesahan;
        }
        // kata_pengantar 
        if ($request->kata_pengantar != null && $request->kata_pengantar != 'null') {
            $file_ext = $request->kata_pengantar->extension();
            $file_name = 'kata_pengantar_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $kata_pengantar = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('kata_pengantar')->move("storage/documents/$Dokumen->user_id", $kata_pengantar);
            $Dokumen->kata_pengantar = $kata_pengantar;
        }
        // ringkasan 
        if ($request->ringkasan != null && $request->ringkasan != 'null') {
            $file_ext = $request->ringkasan->extension();
            $file_name = 'ringkasan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $ringkasan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('ringkasan')->move("storage/documents/$Dokumen->user_id", $ringkasan);
            $Dokumen->ringkasan = $ringkasan;
        }
        // daftar_isi 
        if ($request->daftar_isi != null && $request->daftar_isi != 'null') {
            $file_ext = $request->daftar_isi->extension();
            $file_name = 'daftar_isi_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_isi = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_isi')->move("storage/documents/$Dokumen->user_id", $daftar_isi);
            $Dokumen->daftar_isi = $daftar_isi;
        }
        // daftar_gambar 
        if ($request->daftar_gambar != null && $request->daftar_gambar != 'null') {
            $file_ext = $request->daftar_gambar->extension();
            $file_name = 'daftar_gambar_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_gambar = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_gambar')->move("storage/documents/$Dokumen->user_id", $daftar_gambar);
            $Dokumen->daftar_gambar = $daftar_gambar;
        }
        // daftar_tabel 
        if ($request->daftar_tabel != null && $request->daftar_tabel != 'null') {
            $file_ext = $request->daftar_tabel->extension();
            $file_name = 'daftar_tabel_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_tabel = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_tabel')->move("storage/documents/$Dokumen->user_id", $daftar_tabel);
            $Dokumen->daftar_tabel = $daftar_tabel;
        }
        // daftar_notasi 
        if ($request->daftar_notasi != null && $request->daftar_notasi != 'null') {
            $file_ext = $request->daftar_notasi->extension();
            $file_name = 'daftar_notasi_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_notasi = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_notasi')->move("storage/documents/$Dokumen->user_id", $daftar_notasi);
            $Dokumen->daftar_notasi = $daftar_notasi;
        }
        // abstract_en 
        if ($request->abstract_en != null && $request->abstract_en != 'null') {
            $file_ext = $request->abstract_en->extension();
            $file_name = 'abstract_en_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $abstract_en = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('abstract_en')->move("storage/documents/$Dokumen->user_id",  $abstract_en);
            $Dokumen->abstract_en = $abstract_en;
        }
        // abstract_id 
        if ($request->abstract_id != null && $request->abstract_id != 'null') {
            $file_ext = $request->abstract_id->extension();
            $file_name = 'abstract_id_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $abstract_id = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('abstract_id')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->abstract_id = $abstract_id;
        }
        // bab1 
        if ($request->bab1 != null && $request->bab1 != 'null') {
            $file_ext = $request->bab1->extension();
            $file_name = 'bab1_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab1 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab1')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab1 = $bab1;
        }
        // bab2 
        if ($request->bab2 != null && $request->bab2 != 'null') {
            $file_ext = $request->bab2->extension();
            $file_name = 'bab2_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab2 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab2')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab2 = $bab2;
        }
        // bab3 
        if ($request->bab3 != null && $request->bab3 != 'null') {
            $file_ext = $request->bab3->extension();
            $file_name = 'bab3_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab3 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab3')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab3 = $bab3;
        }
        // bab4 
        if ($request->bab4 != null && $request->bab4 != 'null') {
            $file_ext = $request->bab4->extension();
            $file_name = 'bab4_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $bab4 = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('bab4')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->bab4 = $bab4;
        }
        // lampiran 
        if ($request->lampiran != null && $request->lampiran != 'null') {
            $file_ext = $request->lampiran->extension();
            $file_name = 'lampiran_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lampiran = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('lampiran')->move("storage/documents/$Dokumen->user_id", $lampiran);
            $Dokumen->lampiran = $lampiran;
        }
        // kesimpulan 
        if ($request->kesimpulan != null && $request->kesimpulan != 'null') {
            $file_ext = $request->kesimpulan->extension();
            $file_name = 'kesimpulan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $kesimpulan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('kesimpulan')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->kesimpulan = $kesimpulan;
        }
        // daftar_pustaka 
        if ($request->daftar_pustaka != null && $request->daftar_pustaka != 'null') {
            $file_ext = $request->daftar_pustaka->extension();
            $file_name = 'daftar_pustaka_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $daftar_pustaka = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('daftar_pustaka')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->daftar_pustaka = $daftar_pustaka;
        }
        // paper 
        if ($request->paper != null && $request->paper != 'null') {
            $file_ext = $request->paper->extension();
            $file_name = 'paper_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $paper = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('paper')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->paper = $paper;
        }
        // lembar_persetujuan 
        if ($request->lembar_persetujuan != null && $request->lembar_persetujuan != 'null') {
            $file_ext = $request->lembar_persetujuan->extension();
            $file_name = 'lembar_persetujuan_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $lembar_persetujuan = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('lembar_persetujuan')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->lembar_persetujuan = $lembar_persetujuan;
        }
        // full_dokumen 
        if ($request->full_dokumen != null && $request->full_dokumen != 'null') {
            $file_ext = $request->full_dokumen->extension();
            $file_name = 'full_dokumen_' . $Dokumen->user_id . '_' . time() . '.' . $file_ext;
            $full_dokumen = 'storage/documents/' . $Dokumen->user_id . '/' . $file_name;
            $request->file('full_dokumen')->move("storage/documents/$Dokumen->user_id", $file_name);
            $Dokumen->full_dokumen = $full_dokumen;
        }
        $Dokumen->save();
        if ($request->pembimbing_1) {
            Pembimbing::create([
                'dokumen_id' => $Dokumen->id,
                'user_id' => $request->pembimbing_1
            ]);
        }
        if ($request->pembimbing_2) {
            Pembimbing::create([
                'dokumen_id' => $Dokumen->id,
                'user_id' => $request->pembimbing_2
            ]);
        }
        return $this->successResponse(['status' => true, 'message' => 'Dokumen Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Dokumen = Dokumen::find($id);
        if (!$Dokumen) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        $Dokumen->delete();

        // //Peminjaman Dokumen
        // if (PeminjamanDokumen::where('dokumen_id', $id)->exists()) {
        //     PeminjamanDokumen::where('dokumen_id', $id)->get()->delete();
        // }

        // //Pembimbing
        // if (Pembimbing::where('dokumen_id', $id)->exists()) {
        //     Pembimbing::where('dokumen_id', $id)->get()->delete();
        // }

        // //Bookmark  
        // if (Bookmark::where('dokumen_id', $id)->exists()) {
        //     Bookmark::where('dokumen_id', $id)->get()->delete();
        // }

        return $this->successResponse(['status' => true, 'message' => 'Dokumen Berhasil Dihapus']);
    }

    public function revisiDokumen(Request $request, $id)
    {
        $Dokumen = Dokumen::find($id);
        $Dokumen->status = $request->status;
        $Dokumen->catatan = $request->catatan;
        $Dokumen->save();

        $dataNotif = [
            'judul' => 'Perubahan Status Dokumen',
            'pesan' => 'Status Dokumen ' . $Dokumen->judul . ' ' . $Dokumen->status . '!',
        ];
        $user = User::find($Dokumen->user_id);
        Notification::send($user, new NotifRevisi($dataNotif));

        return $this->successResponse(['status' => true, 'message' => 'Dokumen Berhasil Diubah']);
    }

    public function cekDokumenPerjurusan()
    {
        $cekDokumen = DokumenResource::collection(Dokumen::select('dokumen.*')
            ->join('users', 'users.id', 'dokumen.user_id')
            ->where('users.role', '=', 'Mahasiswa')
            ->where('users.jurusan', '=', Auth::user()->jurusan)
            ->latest()
            ->get());

        return $this->successResponse($cekDokumen);
    }

    public function cariDokumen(Request $request, $id)
    {
        // dd($id);
        $words = explode('%20', $id);
        $kata = join(" ", $words);

        $cekDokumen = Dokumen::select('dokumen.*', 'kategori.nama_kategori')
            ->join('kategori', 'dokumen.kategori_id', 'kategori.id')
            ->where('dokumen.judul', 'LIKE', "%{$kata}%")
            ->orWhere('dokumen.penerbit', 'LIKE', "%{$kata}%")
            ->orWhere('dokumen.nama_pengarang', 'LIKE', "%{$kata}%")
            ->get();

        $cekDokumen = $cekDokumen
            ->where('status', 'Diterima');
        $cekDokumen = SimpelDokumenResource::collection($cekDokumen);

        return $this->successResponse($cekDokumen);
    }

    public function dataDokumen(Request $request)
    {
        $dataDokumen = Dokumen::where('status', 'Diterima')->get();
        if ($request->kategori) {
            $dataDokumen = $dataDokumen->where('kategori_id', $request->kategori);
        }

        $cekDokumen = SimpelDokumenResource::collection($dataDokumen);
        return $this->successResponse($cekDokumen);
    }

    public function dataDokumenTerbaru(Request $request)
    {
        $dataDokumen = Dokumen::where('status', 'Diterima')->latest()->limit(10)->get();

        $cekDokumen = SimpelDokumenResource::collection($dataDokumen);
        return $this->successResponse($cekDokumen);
    }

    public function dataDokumenRekomendasi(Request $request)
    {
        // $dataDokumen = Dokumen::where('status', 'Diterima')
        //     ->latest()->limit(10)->get();
        $dataDokumen = Dokumen::all()->random(5);
        $cekDokumen = SimpelDokumenResource::collection($dataDokumen);
        return $this->successResponse($cekDokumen);
    }
}
