<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\UserResource;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->role) {
            $user = User::where('role', $request->role)->paginate(10);
        } else {
            $user = User::paginate(10);
        }

        $UserResource = UserResource::collection($user)->response()->getData(true);
        return $this->successResponse($UserResource);
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
        $data = User::where('dokumen_id', $request->dokumen_id)->where('user_id', Auth::user()->id)->first();

        if ($data) {
            $data->delete();
            return $this->successResponse(['status' => true, 'message' => 'User Berhasil Dihapus']);
        } else {
            $User = new User($request->all() + ["user_id" => Auth::user()->id]);
            $User->save();

            return $this->successResponse(['status' => true, 'message' => 'User Berhasil Ditambahkan']);
        }
    }

    public function show($id)
    {
        $User = User::find($id);
        if (!$User) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        return $this->successResponse($User);
    }

    public function update(Request $request, $id)
    {

        $data = User::where('dokumen_id', $id)->where('user_id', Auth::user()->id)->first();

        if ($data) {
            $data->delete();
            return $this->successResponse(['status' => true, 'message' => 'User Berhasil Dihapus']);
        } else {
            $User = new User(["user_id" => Auth::user()->id, "dokumen_id" => $id]);
            $User->save();
            return $this->successResponse(['status' => true, 'message' => 'User Berhasil Ditambahkan']);
        }
    }

    public function destroy($id)
    {
        $User = User::find($id);
        if (!$User) {
            return $this->errorResponse('Data tidak ditemukan', 422);
        }

        $User->delete();
        return $this->successResponse(['status' => true, 'message' => 'User Berhasil Dihapus']);
    }

    public function bebasPustaka()
    {
        try {
            User::where('nim', request()->nim)->firstOrFail();
        } catch (\Throwable $th) {
            return $this->errorResponse('NIM tidak ditemukan', 400);
        }

        $getData = Dokumen::select('*')
            ->join('kategori', 'kategori.id', 'dokumen.kategori_id')
            ->join('users', 'users.id', 'dokumen.user_id')
            ->where('nim', request()->nim)
            ->where('status', 'Diterima')
            ->pluck('nama_kategori')->toArray();
        if (in_array('Kerja Praktek', $getData) && in_array('Tugas Akhir', $getData)) {
            return $this->successResponse('Anda sudah bebas pustaka!');
        }
        return $this->errorResponse('Anda belum bebas pustaka', 400);
    }
}
