<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $Kategori = KategoriResource::collection(Kategori::all());
        // visitor()->visit();
        return $this->successResponse($Kategori);
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'nama_kategori' => 'required',
                'detail' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $Kategori = new Kategori();
        $Kategori->nama_kategori = $request->nama_kategori;
        $Kategori->detail = $request->detail;
        $Kategori->berkas = $request->berkas;
        $Kategori->isPembimbing = $request->isPembimbing;
        $Kategori->save();

        return $this->successResponse(['status' => true, 'message' => 'Kategori Berhasil Ditambahkan']);
    }

    public function show($id)
    {
        $Kategori = new KategoriResource(Kategori::findOrFail($id));
        if (!$Kategori) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Kategori);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $Kategori = Kategori::find($id);
        if (!$Kategori) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        // $data[] = null;
        // foreach (json_decode($Kategori->Berkas) as $key => $value) {
        //     $data = [$key => True];
        // }
        // return ($data);
        $Kategori->Save();
        $Kategori = Kategori::find($Kategori->id)->update([
            'nama_kategori' => $request->nama_kategori,
            'detail' => $request->detail,
            'berkas' => $request->berkas,
            'isPembimbing' => $request->isPembimbing,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Kategori Berhasil Diubah']);
    }

    public function destroy($id)
    {
        $Kategori = Kategori::find($id);
        if (!$Kategori) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Kategori->delete();
        return $this->successResponse(['status' => true, 'message' => 'Kategori Berhasil Dihapus']);
    }
}
