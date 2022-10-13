<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return $this->errorResponse(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors());
    //     }

    //     $response = Http::acceptJson()->post('https://api-gerbang.itk.ac.id/api/siakad/login', [
    //         'email' => $request->email,
    //         'password' => $request->password
    //     ]);
    //     // return $response->json();
    //     if ($response->ok()) {
    //         $json = $response->json();
    //         $mahasiswa = $json['data'];

    //         if (array_key_exists("PE_Nip", $mahasiswa['biodata'])) {
    //             //dosen
    //             $mahasiswalogin = User::updateOrCreate(
    //                 [
    //                     'nim' => $mahasiswa['XNAMA']
    //                 ],
    //                 [
    //                     'nim' => $mahasiswa['XNAMA'],
    //                     'name' => $mahasiswa['USERDESC'],
    //                     'email' => $mahasiswa['biodata']['PE_Email'],
    //                     'role' => 'Dosen'
    //                 ]
    //             );
    //             return $this->respondWithToken($mahasiswalogin);
    //             // $token = $mahasiswalogin->createToken('auth_token')->plainTextToken;
    //             // return $this->successResponse([
    //             //     'message' => 'Authentikasi Berhasil',
    //             //     'nim' => $mahasiswa['XNAMA'],
    //             //     'name' => $mahasiswa['USERDESC'],
    //             //     'email' => $mahasiswa['biodata']['PE_Email'],
    //             //     'role' => 'Dosen',
    //             //     'token' => $token
    //             // ]);
    //         } elseif (array_key_exists("MA_Nrp", $mahasiswa['biodata'])) {
    //             // mahasiswa
    //             $mahasiswalogin = User::updateOrCreate(
    //                 [
    //                     'nim' => $mahasiswa['XNAMA']
    //                 ],
    //                 [
    //                     'nim' => $mahasiswa['XNAMA'],
    //                     'name' => $mahasiswa['USERDESC'],
    //                     'email' => $mahasiswa['biodata']['MA_Email'],
    //                     'jurusan' => $mahasiswa['biodata']['nama_jurusan'],
    //                     'prodi' => $mahasiswa['biodata']['prodi']['Nama_Prodi'],
    //                     'angkatan' => $mahasiswa['biodata']['MA_Tahun_Masuk'],
    //                     'role' => 'Mahasiswa',
    //                 ]
    //             );
    //             $login = Auth::attempt(['email' => $mahasiswalogin->email, 'password' => 123123123]);
    //             // return $login;
    //             return $this->respondWithToken($login);
    //             // $token = $mahasiswalogin->createToken('auth_token')->plainTextToken;
    //             // return $this->successResponse([
    //             //     'message' => 'Authentikasi Berhasil',
    //             //     'nim' => $mahasiswa['XNAMA'],
    //             //     'name' => $mahasiswa['USERDESC'],
    //             //     'email' => $mahasiswa['biodata']['MA_Email'],
    //             //     'jurusan' => $mahasiswa['biodata']['nama_jurusan'],
    //             //     'prodi' => $mahasiswa['biodata']['prodi']['Nama_Prodi'],
    //             //     'angkatan' => $mahasiswa['biodata']['MA_Tahun_Masuk'],
    //             //     'role' => 'Mahasiswa',
    //             //     'token' => $token
    //             // ]);
    //         }
    //     } else {
    //         // return 'erza';
    //         if (!$token = Auth::attempt($request->only('email', 'password'))) {
    //             return $this->errorResponse('Unauthorized', 401);
    //         }
    //         // return $token;
    //         return $this->respondWithToken($token);
    //     }
    // }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->successResponse(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => "success",
            'code' => 200,
            'data' => [
                "message" => "Authentikasi Berhasil",
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60 * 24,
                'user' => auth()->user()
            ],
        ]);
    }
}
