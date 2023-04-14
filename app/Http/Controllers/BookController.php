<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{

    public function store(Request $request) {
        $user = Auth::user(); //現在登入的人
        $validated = $this->validate($request,[
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);
        return $user->books()->create($validated);
    }
}
