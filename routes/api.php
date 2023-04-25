<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('user')->group(function() {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function() {
       Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
       Route::apiResource('books', \App\Http\Controllers\BookController::class)
           ->only('index', 'show', 'store', 'update', 'destroy');
    });
});

Route::post('photo', function (Request $request) {
    $request->validate([
        // 驗證使用者所傳檔案為圖片
       'image' => ['image'],
    ]);
    // 上傳的檔案中，找尋key為images的內容
   $images = $request->file('images');
//   dd($images);
//    dd($images[0]->getPath());
    // 原始上傳的副檔名一起存起來，上傳.png 就會存成.png
   $extensionName = $images[1]->getClientOriginalExtension();
    // 給一串16字元隨機亂碼，避免惡意使用者猜到檔案，也可以用UUID
   $path = \Illuminate\Support\Str::random() . '.' . $extensionName;
    // 取得上傳檔案的內容
   $content = $images[1]->getContent();
    // 真正存入檔案(原本的用法)
   \Illuminate\Support\Facades\Storage::put($path, $content);
   return 'upload file';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
