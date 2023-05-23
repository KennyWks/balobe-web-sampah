<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
            // 'password' => 'required|string|min:8|max:50',
        ];

        $input = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'email' => '*Kolom :attribute tidak valid.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if (Auth::attempt($input)) {            
            Auth::logoutOtherDevices($request->input('password'));
            $request->session()->regenerate();
            return redirect('/admin/beranda');
        }

        return redirect('/')->withErrors($validator)->withInput()->with('error', 'Login Gagal!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
