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
    public function index()
    {
        $Bookmark = BookmarkResource::collection(Bookmark::all());
        return $this->successResponse($Bookmark);
    }

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

    public function show($id)
    {
        $Bookmark = Bookmark::find($id);
        if (!$Bookmark) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($Bookmark);
    }

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
