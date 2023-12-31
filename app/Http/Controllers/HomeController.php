<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $client;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guzzle $client)
    {
        $this->middleware(['auth', 'refresh.token']);
        $this->client = $client;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $tweets = collect();

        if ($request->user()->token) {
            $response = $this->client->get('http://127.0.0.1:8000/api/tweets', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $request->user()->token->access_token
                ]
            ]);

            $tweets = collect(json_decode($response->getBody()));
        }
        return view('home')->with([
            'tweets' => $tweets
        ]);
    }
}
