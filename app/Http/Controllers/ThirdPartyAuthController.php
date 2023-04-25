<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ThirdPartyAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            $provider . '_id' => $providerUser->id,
        ], [
            'name' => $providerUser->name,
            'email' => $providerUser->email,
            $provider . '_token' => $providerUser->token,
            $provider . '_refresh_token' => $providerUser->refreshToken,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return $user;
    }
}
