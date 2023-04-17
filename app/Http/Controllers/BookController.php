<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
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
//        return $user->books()->create($validated); //原本的情況
         return BookResource::make($user->books()->create($validated)); //使用resource
        // 這裡使用 make() ，底層是會變成 new BookResource，BookResource把原本的東西實例化出來

        //測試用
//        return $this->authorize('create', [Book::class]);
        // routes 那裡有設定身份驗證的機制為 Route::middleware('auth:api')
        // 這邊的 Book::class 會 call BookPolicy 的 create method
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', [Book::class]);  //$this->authorize('policy動作', [連結的 Model]); BookPolicy.php
        $books = Book::latest()->with('user'); //照時間排序
        if ($request->boolean('owned')) { //如果前端回傳的 header 有包含'owned'內容
            $books->where('user_id', Auth::user()->getKey()); // 只能看到自己的書
            // $books中，挑選 書本 user_id = 登入使用者id 的書
        }
        return BookCollection::make($books->paginate());  //資料太多的話不建議all()，會改用paginate()
    }

    public function update(Request $request, Book $book) {
        $this->authorize('update', [Book::class, $book]);
        $validated = $this->validate($request, [
            'name' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
        ]);
        $book->update($validated);
        return $book;
    }

    public function destroy(Book $book) {
        $this->authorize('delete', [Book::class, $book]);
        $book->delete();
        return "Deleted successful";
    }

    public function show(Request $request, Book $book) {
        $this->authorize('view', [Book::class, $book]);
        return $book;
    }
}
