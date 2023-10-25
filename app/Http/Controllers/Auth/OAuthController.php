<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;

class OAuthController extends Controller
{
    protected $client;
    /**
     * Class constructor.
     */
    public function __construct(Guzzle $client)
    {
        $this->middleware('auth');
        $this->client = $client;
    }
    
    public function redirect() 
    {
        $query = http_build_query([
            'client_id' => '3',
            'redirect_uri' => 'http://127.0.0.1:8001/auth/passport/callback',
            'response_type' => 'code',
            'scope' => ''
        ]);

        return redirect('http://127.0.0.1:8000/oauth/authorize?'. $query);
    }

    public function callback(Request $request) 
    {
       $response = $this->client->post('http://127.0.0.1:8000/oauth/token', [
            'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '3',
            'client_secret' => 'QKIU8yEKliQ4vTODT0UbPBlQszUsSID2tWRL9IoU',
            'redirect_uri' => 'http://127.0.0.1:8001/auth/passport/callback',
            'code' => $request->code
            ]
        ]);

        $response = json_decode($response->getBody());

        $request->user()->token()->delete();

        $request->user()->token()->create([
            'access_token' => $response->access_token
        ]);

        return redirect('/home');
    }
}
