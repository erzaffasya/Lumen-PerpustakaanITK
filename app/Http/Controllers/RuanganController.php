<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanRuanganResource;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

class RuanganController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/ruangan",
     *  tags={"Ruangan"},
     *  summary="Get ruangan",
     *  @OA\Response(response=200, description="Get ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        $Ruangan = Ruangan::all();
        return $this->successResponse($Ruangan);
    }

    /**
     * @OA\Post(
     *  path="/api/ruangan",
     *  tags={"Ruangan"},
     *  summary="Post ruangan",
     *  @OA\Response(response=200, description="Post ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
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
        
        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan Berhasil Ditambahkan',
            'code' => 200,
            'data' => new PeminjamanRuanganResource($Ruangan->id)
        ]);
     
    }

    /**
     * @OA\Get(
     *  path="/api/ruangan/{id}",
     *  tags={"Ruangan"},
     *  summary="Get ruangan",
     *  @OA\Response(response=200, description="Get ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function show($id)
    {
        $Ruangan = Ruangan::find($id);
        if (!$Ruangan) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Ruangan);
    }

    /**
     * @OA\Put(
     *  path="/api/ruangan/{id}",
     *  tags={"Ruangan"},
     *  summary="Put ruangan",
     *  @OA\Response(response=200, description="Put ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
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

    /**
     * @OA\Delete(
     *  path="/api/ruangan/{id}",
     *  tags={"Ruangan"},
     *  summary="DELETE ruangan",
     *  @OA\Response(response=200, description="DELETE ruangan"),
     *  security={{ "apiAuth": {} }}
     * )
     */
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
