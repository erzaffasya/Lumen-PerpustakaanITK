<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\YudisiumResource;
use App\Models\Yudisium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YudisiumController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/Yudisium",
     *  tags={"Yudisium"},
     *  summary="Get the list of resources",
     *  @OA\Response(response=200, description="Return a list of resources"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        $Yudisium = YudisiumResource::collection(Yudisium::all());
        return $this->successResponse($Yudisium);
    }

    /**
     * @OA\Post(
     *     path="/api/Yudisium",
     *     tags={"Yudisium"},
     *     summary="Tambah Data Yudisium",
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
     *                     property="periode",
     *                     description="Nama Yudisium",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="tahun",
     *                     description="tahun Yudisium",
     *                     type="string"
     *                 ), 
     *                 @OA\Property(
     *                     property="expired_at",
     *                     description="expired_at",
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
                'periode' => 'required',
                'tahun' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $Yudisium = new Yudisium();
        $Yudisium->periode = $request->periode;
        $Yudisium->tahun = $request->tahun;
        $Yudisium->expired_at = $request->expired_at;
        $Yudisium->save();

        return $this->successResponse(['status' => true, 'message' => 'Yudisium Berhasil Ditambahkan']);
    }

    /**
     * @OA\Get(
     *  path="/api/Yudisium/{id}",
     *  tags={"Yudisium"},
     *  summary="Get Yudisium by Yudisium id",
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
        $Yudisium = new YudisiumResource(Yudisium::findOrFail($id));
        if (!$Yudisium) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Yudisium);
    }

    public function update(Request $request, $id)
    {
        $Yudisium = Yudisium::find($id);
        if (!$Yudisium) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        $Yudisium->Save();
        $Yudisium = Yudisium::find($Yudisium->id)->update([
            'periode' => $request->periode,
            'tahun' => $request->tahun,
            'expired_at' => $request->expired_at,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Yudisium Berhasil Diubah']);
    }

    /**
     * @OA\Delete(
     *     path="/api/Yudisium/{id}",
     *     tags={"Yudisium"},
     *     summary="Deletes a Yudisium",
     *     operationId="deleteYudisium",
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
     *         description="Yudisium not found",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function destroy($id)
    {
        $Yudisium = Yudisium::find($id);
        if (!$Yudisium) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Yudisium->delete();
        return $this->successResponse(['status' => true, 'message' => 'Yudisium Berhasil Dihapus']);
    }
}
