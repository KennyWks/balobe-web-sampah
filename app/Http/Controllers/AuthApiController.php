<?php

namespace App\Http\Controllers;

use App\Models\User;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
            // 'password' => 'required|string|min:8|max:50',
        ]);

        //Send failed response if request is not valid
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
            JWTAuth::parseToken()->invalidate($token);
            return response()->json([
                'success'   => true,
                'message' => 'Berhasil logout'
            ], 200);
        } catch (TokenExpiredException $exception) {
            return response()->json([
                'success'   => true,
                'message' => 'Token expired'
            ], 201);
        } catch (TokenInvalidException $exception) {
            return response()->json([
                'success'   => false,
                'message' => 'Token invalid'
            ], 401);
        } catch (JWTException $exception) {
            return response()->json([
                'success'   => true,
                'message' => 'Token missing'
            ], 201);
        }
    }
}