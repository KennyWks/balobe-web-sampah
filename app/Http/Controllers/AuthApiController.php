<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
            // 'password' => 'required|string|min:8|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        
        try {
            $dataUser = User::select('*')->join('user_details', 'user_details.user_id', "=", "users.user_id")->join('roles', 'roles.role_id', "=", "users.role_id")->where('email', $request->email);

            if ($dataUser->count() > 0) {
                $data = $dataUser->first();
                $rowsUser = [
                    'user_id' => $data->user_id,
                    'email' => $data->email,
                    'name' => $data->name,
                    'jk' => $data->jk,
                    'tgl_lahir' => $data->tgl_lahir,
                    'no_hp' => $data->no_hp,
                    'pekerjaan' => $data->pekerjaan,
                    'alamat' => $data->alamat,
                ];

                $token = JWTAuth::claims($rowsUser)->attempt($credentials);
                if ($token) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'message' => 'Login berhasil.',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'success' => false,
                        'message' => 'Email atau password salah.',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Data anda tidak ditemukan.',
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Token tidak valid.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        try {
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'Proses keluar berhasil'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Proses keluar gagal'
            ], 500);
        }
    }

    public function updateUser(Request $request, $user_id){
        $input = $request->only('name', 'jk', "tglLahir", "noHP", "email", "password", "pekerjaan");

        $validator = Validator::make($input, [
            "name" => "required",
            "jk" => "required",
            "tglLahir" => "required|date",
            "noHP" => "required|numeric|digits_between:11,12",
            "email" => "required|email",
            "password" => "string|min:8",
            "pekerjaan" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
            try {
                User::where('user_id', $user_id)->update([
                    "email" => $request->input("email"),
                    "password" => Hash::make($request->input("password")),
                ]);

                UserDetails::where('user_id', $user_id)->update([
                    "name" => $request->input("name"),
                    "jk" => $request->input("jk"),
                    "tgl_lahir" => $request->input("tgl_lahir"),
                    "no_hp" => $request->input("no_hp"),
                    "pekerjaan" => $request->input("pekerjaan"),
                    // "alamat" => $request->input("alamat"),
                ]);

                $token = JWTAuth::claims($input);
                if ($token) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'message' => 'Data berhasil diubah.',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'success' => false,
                        'message' => 'Data berhasil diubah.',
                    ]);
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 500,
                    'success' => false,
                    'message' => 'Failed!',
                ]);
            }
        }
    }
}