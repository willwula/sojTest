<?php

namespace App\Http\Controllers;

use App\Models\Book;
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

        //測試用
//        return $this->authorize('create', [Book::class]);
        // routes 那裡有設定身份驗證的機制為 Route::middleware('auth:api')
        // 這邊的 Book::class 會 call BookPolicy 的 create method
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', [Book::class]);  //$this->authorize('policy動作', [連結的 Model]); BookPolicy.php
        $books = Book::latest(); //照時間排序
        if ($request->boolean('owned')) { //如果前端回傳的 header 有包含'owned'內容
            $books->where('user_id', Auth::user()->getKey()); // 只能看到自己的書
            // $books中，挑選 書本 user_id = 登入使用者id 的書
        }
        return $books->paginate();  //資料太多的話不建議all()，會改用paginate()
    }
}
