<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        // dd($url);
        return response()->json(['redirect_url' => $url], 202);
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $socialiteUser = Socialite::driver($provider)->stateless()->user();

        $appUser = User::firstOrCreate(
            [
                'email' => $socialiteUser->getEmail()
            ],
            [
                'name' => $socialiteUser->getName(),
                'password' => Str::random(64),
                // 'role_id' => 1,
            ]
        );

        $userSocialAccount = SocialAccount::firstOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId()
            ],
            [
                'user_id' => $appUser->id,
            ]
        );
        // dd($appUser);
        Auth::guard()->login($appUser);
        request()->session()->regenerate();

        return response()->json(['status' => 'User logged in', 'user' => new UserResource($appUser->fresh())], 202);
        $user->token;
    }
}
