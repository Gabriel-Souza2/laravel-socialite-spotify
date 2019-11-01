<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    public function playlists()
    {
        $token = decrypt(Auth::user()->access_spotify);
        $client = new Client();

        $headers = [
            'Authorization' => "Bearer $token"
        ];

        $request = new Request('GET', 'https://api.spotify.com/v1/me/playlists',
            $headers);

        $promise = $client->sendAsync($request)->then(function ($response) {
            $data = $response->getBody()->getContents();
            return $data;
        });

        $promise->wait();
    }
}
