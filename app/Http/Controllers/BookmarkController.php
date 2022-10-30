<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/bookmark",
     *  tags={"Bookmark"},
     *  summary="Get Bookmark",
     *  @OA\Response(response=200, description="Bookmark"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        if (Auth::user()->role != "Admin") {
            $Bookmark = Bookmark::where('user_id',  Auth::id())->get();
        } else {
            $Bookmark = Bookmark::all();
        }
        $Bookmark = BookmarkResource::collection(Bookmark::all());
        return $this->successResponse($Bookmark);
    }

    /**
     * @OA\Post(
     *  path="/api/bookmark",
     *  tags={"Bookmark"},
     *  summary="POST Bookmark",
     *  @OA\Response(response=200, description="POST Bookmark"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'dokumen_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $data = Bookmark::where('dokumen_id', $request->dokumen_id)->where('user_id', Auth::user()->id)->first();

        if ($data) {
            $data->delete();
            return $this->successResponse(['status' => true, 'message' => 'Bookmark Berhasil Dihapus']);
        } else {
            $Bookmark = new Bookmark($request->all() + ["user_id" => Auth::user()->id]);
            $Bookmark->save();

            return $this->successResponse(['status' => true, 'message' => 'Bookmark Berhasil Ditambahkan']);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/bookmark/{id}",
     *  tags={"Bookmark"},
     *  summary="GET Bookmark",
     *  @OA\Response(response=200, description="GET Bookmark"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function show($id)
    {
        $Bookmark = Bookmark::find($id);
        if (!$Bookmark) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Bookmark);
    }

    /**
     * @OA\Put(
     *  path="/api/bookmark",
     *  tags={"Bookmark"},
     *  summary="PUT Bookmark",
     *  @OA\Response(response=200, description="PUT Bookmark"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function update(Request $request, $id)
    {

        $data = Bookmark::where('dokumen_id', $id)->where('user_id', Auth::user()->id)->first();

        if ($data) {
            $data->delete();
            return $this->successResponse(['status' => true, 'message' => 'Bookmark Berhasil Dihapus']);
        } else {
            $Bookmark = new Bookmark(["user_id" => Auth::user()->id, "dokumen_id" => $id]);
            $Bookmark->save();
            return $this->successResponse(['status' => true, 'message' => 'Bookmark Berhasil Ditambahkan']);
        }
    }

    /**
     * @OA\Delete(
     *  path="/api/bookmark",
     *  tags={"Bookmark"},
     *  summary="DELETE Bookmark",
     *  @OA\Response(response=200, description="DELETE Bookmark"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function destroy($id)
    {
        $Bookmark = Bookmark::find($id);
        if (!$Bookmark) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $Bookmark->delete();
        return $this->successResponse(['status' => true, 'message' => 'Bookmark Berhasil Dihapus']);
    }
}
