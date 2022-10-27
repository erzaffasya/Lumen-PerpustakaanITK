<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/kategori",
     *  tags={"kategori"},
     *  summary="Get the list of resources",
     *  @OA\Response(response=200, description="Return a list of resources"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        $Kategori = KategoriResource::collection(Kategori::all());
        return $this->successResponse($Kategori);
    }

    /**
     * @OA\Post(
     *     path="/api/kategori",
     *     tags={"kategori"},
     *     summary="Tambah Data Kategori",
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
     *                     property="nama_kategori",
     *                     description="Nama Kategori",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="detail",
     *                     description="Detail Kategori",
     *                     type="string"
     *                 ), 
     *                 @OA\Property(
     *                     property="berkas",
     *                     description="Berkas",
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

    /**
     * @OA\Get(
     *  path="/api/kategori/{id}",
     *  tags={"kategori"},
     *  summary="Get kategori by kategori id",
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
        $Kategori = new KategoriResource(Kategori::findOrFail($id));
        if (!$Kategori) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Kategori);
    }

    public function update(Request $request, $id)
    {
        $Kategori = Kategori::find($id);
        if (!$Kategori) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }
        $Kategori->Save();
        $Kategori = Kategori::find($Kategori->id)->update([
            'nama_kategori' => $request->nama_kategori,
            'detail' => $request->detail,
            'berkas' => $request->berkas,
            'isPembimbing' => $request->isPembimbing,
        ]);

        return $this->successResponse(['status' => true, 'message' => 'Kategori Berhasil Diubah']);
    }

    /**
     * @OA\Delete(
     *     path="/api/kategori/{id}",
     *     tags={"kategori"},
     *     summary="Deletes a kategori",
     *     operationId="deletekategori",
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
     *         description="kategori not found",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
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
