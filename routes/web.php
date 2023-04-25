<?php

use App\Http\Controllers\ThirdPartyAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::prefix('auth')->group(function () {
   Route::get('{provider}', [ThirdPartyAuthController::class, 'redirectToProvider']);
   Route::get('/{provider}/callback', [ThirdPartyAuthController::class, 'handleProviderCallback']);
});

//Route::get('/auth/github', function () {
//    // 使用者按下後，會連到第三方認證登入頁面
//    // 路徑可依自己喜歡設定，這裡設定 /auth/github，記得前端連結也要相同
//    return Socialite::driver('github')->redirect();
//});
//Route::get('/auth/github/callback', function () {
//    // 第三方登入完成後，第三方服務轉回專案的 route
//    // 路徑可依自己喜歡設定，這裡設定 /auth/github/callback，記得 github & .env 也要相同
////    dd(Socialite::driver('github'));
//    $user = Socialite::driver('github')->user();
//
//
//    //$user->token
//    return"OAuth successful"; //test
//});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
