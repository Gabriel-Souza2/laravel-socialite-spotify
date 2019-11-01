<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('spotify')
            ->setScopes(['user-read-email', 'playlist-read-private'])
            ->stateless()
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $userSpotify = Socialite::driver('spotify')->stateless()->user();

        $user = User::where('email', $userSpotify->getEmail())->first();

        if(!$user){
            $user = User::create([
                'email' => $userSpotify->getEmail(),
                'password' => \bcrypt(\str_random(10)),
                'name' => $userSpotify->getName()
            ]);
        }

        $user->access_spotify = \encrypt($userSpotify->token);
        $user->save();

        $token = JWTAuth::fromUser($user);

        return [
            'token' => $token
        ];
    }
}
