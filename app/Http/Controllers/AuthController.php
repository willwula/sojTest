<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credential = $this->validate($request, [
            'email' => 'email|required|max:255',
            'password' => 'required|alpha_num:ascii|min:6|max:12'
        ]);
        $token = Auth::guard('api')->attempt($credential);
        abort_if( !$token, \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST, "帳號密碼錯誤");
        return response(['data' => $token]);
    }

    public function logout() {
        Auth::logout();
        return response()->noContent();
    }
}
