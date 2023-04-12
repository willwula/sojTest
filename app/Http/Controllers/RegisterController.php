<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request) {
        $validated = $this->validate($request, [
            'name' => 'required|string|max:20|min:2',
            'email' => 'required|email|max:255|',
            'password' => 'required|alpha_num:ascii|min:4|max:255|confirmed'
        ]);
        abort_if(
            User::where('email', $request->input('email'))->first(),  //此處為bool
            Response::HTTP_BAD_REQUEST,
            __('auth.duplicate email')
        );
        $user = User::create(
            array_merge(
                $validated, ['password' => Hash::make($validated['password'])]
            )
        );
        return response([
            'data' => $user,
            'message' => '註冊成功',
            ],201);
    }
}
