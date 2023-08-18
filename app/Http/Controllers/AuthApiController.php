<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserDetails;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
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
                        'success' => true,
                        'message' => 'Login berhasil.',
                        'token' => $token,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email atau password salah.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data anda tidak ditemukan.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found!',
                    ], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil logout!',
                ], 201);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired!',
            ], 404);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalid!',
            ], 400);
        }
    }

    public function updateOrCreateUser(Request $request){
        $input = $request->only('name', 'jk', "tgl_lahir", "no_hp", "email", "password", "pekerjaan");
        
        $validator = Validator::make($input, [
            "name" => "required",
            "tgl_lahir" => $request->input("tgl_lahir") ? "date" : "",
            "no_hp" => "required|numeric|digits_between:11,12",
            "email" => "required|email",
            "password" => "required|string|min:8",
            "pekerjaan" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $user_id = [
            "user_id" => $request->input("user_id"),
        ];

        $credentials = [
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
        ];

        $rowsUser = [
            "name" => $request->input("name"),
            "jk" => $request->input("jk"),
            "tgl_lahir" => $request->input("tgl_lahir"),
            "no_hp" => $request->input("no_hp"),
            "pekerjaan" => $request->input("pekerjaan"),
            // "alamat" => $request->input("alamat"),
        ];
        
        try {
            $user = User::updateOrCreate($user_id, $credentials);
            if($user->wasRecentlyCreated){
                $collectionUser = $user->getAttributes();
                $rowsUser['user_id'] = $collectionUser['user_id']; 

                $role = Roles::where('role', 'masyarakat')->first();
                User::where('user_id', $rowsUser['user_id'])->update([
                    'role_id' => $role['role_id'],
                ]);
            }
            $userDetails = UserDetails::updateOrCreate($user_id, $rowsUser);
            if ($userDetails) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diproses.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gagal diubah.',
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed!',
            ], 500);
        }
    }

    public function uploadPhoto(Request $request){

        $rules = [
            'user_id' => 'required',
            'photo' => 'required',
            'type' => 'required'
        ];

        $input = [
            'user_id' => $request->input('user_id'),
            'photo' => $request->input('photo'),
            'type' => $request->input('type'),
        ];

        $messages = [
            'required' => '*Kolom :attribute wajib diisi.',
        ];

        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $user_id = $request->input('user_id');
            $folderPath = "unggah/users/";
            $explodeImage = explode("/", $request->input('type'));
            $imageType = $explodeImage[1];
            $image_base64 = base64_decode($request->input('photo'));
            $file = $folderPath . $user_id . '.' .$imageType;
            file_put_contents($file, $image_base64);

            UserDetails::where("user_id", $user_id)->update([
                "photo" => "/$file"
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diunggah!',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th,
            ], 500);
        }

    }
}