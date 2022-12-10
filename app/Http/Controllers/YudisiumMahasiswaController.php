<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\YudisiumMahasiswaResource;
use App\Models\YudisiumMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class YudisiumMahasiswaController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/YudisiumMahasiswa",
     *  tags={"YudisiumMahasiswa"},
     *  summary="Get the list of resources",
     *  @OA\Response(response=200, description="Return a list of resources"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $YudisiumMahasiswa = YudisiumMahasiswa::where('user_id',  Auth::id())->get();
        } else {
            $YudisiumMahasiswa = YudisiumMahasiswa::all();
        }

        if ($request->filter) {
            switch ($request->filter) {
                case 'pengajuan':
                    $dataYudisiumMahasiswa = YudisiumMahasiswaResource::collection($YudisiumMahasiswa
                        ->where('status_final', '!=', true));
                    break;
                case 'riwayatPengajuan':
                    $dataYudisiumMahasiswa = YudisiumMahasiswaResource::collection($YudisiumMahasiswa
                        ->where('status_final', true));
                    break;
                default:
                    break;
            }
        } else {
            $dataYudisiumMahasiswa = YudisiumMahasiswaResource::collection($YudisiumMahasiswa);
        }

        // $YudisiumMahasiswa = YudisiumMahasiswaResource::collection($YudisiumMahasiswa);
        return $this->successResponse($dataYudisiumMahasiswa);
    }

    /**
     * @OA\Post(
     *     path="/api/YudisiumMahasiswa",
     *     tags={"YudisiumMahasiswa"},
     *     summary="Tambah Data YudisiumMahasiswa",
     *     operationId="updatePetWithForm",
     *     @OA\Response(
     *         response=200,
     *         description="Sukses Ditambahkan"
     *     ),
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="yudisium_id",
     *                     description="Nama YudisiumMahasiswa",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="user_id YudisiumMahasiswa",
     *                     type="string"
     *                 ), 
     *                 @OA\Property(
     *                     property="status_berkas",
     *                     description="status_berkas",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="isPembimbing",
     *                     description="Pembimbing Boolean",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'yudisium_id' => 'required',
                // 'user_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (YudisiumMahasiswa::where('user_id', Auth::user()->id)
            ->where('yudisium_id', $request->yudisium_id)
            ->exists()
        ) {
            return $this->errorResponse('Anda sudah mengajukan permohonan yudisium', 422);
        }

        $YudisiumMahasiswa = new YudisiumMahasiswa();
        $YudisiumMahasiswa->yudisium_id = $request->yudisium_id;
        $YudisiumMahasiswa->user_id = Auth::user()->id;
        $YudisiumMahasiswa->save();

        return $this->successResponse(['status' => true, 'message' => 'YudisiumMahasiswa Berhasil Ditambahkan']);
    }

    /**
     * @OA\Get(
     *  path="/api/YudisiumMahasiswa/{id}",
     *  tags={"YudisiumMahasiswa"},
     *  summary="Get YudisiumMahasiswa by YudisiumMahasiswa id",
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,*),
     *    @OA\Response(response=200, description="Success"),
     *    security={{ "apiAuth": {} }}
     * )
     */
    public function show($id)
    {
        $YudisiumMahasiswa = new YudisiumMahasiswaResource(YudisiumMahasiswa::findOrFail($id));
        if (!$YudisiumMahasiswa) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($YudisiumMahasiswa);
    }

    public function update(Request $request, $id)
    {
        $YudisiumMahasiswa = YudisiumMahasiswa::find($id);
        if (!$YudisiumMahasiswa) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        $YudisiumMahasiswa->yudisium_id = $request->yudisium_id ?? $YudisiumMahasiswa->yudisium_id;
        $YudisiumMahasiswa->user_id = $request->user_id ?? $YudisiumMahasiswa->user_id;
        $YudisiumMahasiswa->status_berkas = $request->status_berkas ?? $YudisiumMahasiswa->status_berkas;
        $YudisiumMahasiswa->status_pinjam = $request->status_pinjam ?? $YudisiumMahasiswa->status_pinjam;
        $YudisiumMahasiswa->status_final = $request->status_final ?? $YudisiumMahasiswa->status_final;
        $YudisiumMahasiswa->save();
        return $this->successResponse(['status' => true, 'message' => 'YudisiumMahasiswa Berhasil Diubah']);
    }

    /**
     * @OA\Delete(
     *     path="/api/YudisiumMahasiswa/{id}",
     *     tags={"YudisiumMahasiswa"},
     *     summary="Deletes a YudisiumMahasiswa",
     *     operationId="deleteYudisiumMahasiswa",
     *     @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,*),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data Berhasil Dihapus",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="YudisiumMahasiswa not found",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function destroy($id)
    {
        $YudisiumMahasiswa = YudisiumMahasiswa::find($id);
        if (!$YudisiumMahasiswa) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $YudisiumMahasiswa->delete();
        return $this->successResponse(['status' => true, 'message' => 'YudisiumMahasiswa Berhasil Dihapus']);
    }
}
