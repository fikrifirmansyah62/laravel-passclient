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
        $this->middleware('auth');
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
                    // 'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiZDZiMjAzYjdkMGY0NDUyZTZjYjA2MDU3OWVhZjA2M2VlZWU0ZDRkZjQ0YTljMmQ3NGI3MDMzMmRkMTdkZTQwYzVmNjQ1YWNlNDFkZDcwYWEiLCJpYXQiOjE2OTgyMDQwMzEsIm5iZiI6MTY5ODIwNDAzMSwiZXhwIjoxNzI5ODI2NDI5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bDaGDAvedHtIUUR0JYWUBDjYo2v3QTvxQ96gSFH7vYceWYzcLYSbR9Vq26arzWBHEok7qDN9hSELStc3QhsXO84QuTlFq9Q7A3DUtFoI23ObWpgWMbyg4jd9Xd8rQmDCPgvOt7Hb9W-PD9R4Ns2zJUx6ZGXw2r8c5REV5uv3_e6Tb0nmsleJKf9iG982tfnIjj1pG4KgwMlfuXxFWxOMEdjZ9WtzKbLxN0ucgi_3j8R7_L3yt-jZc8p0j5YAkUt0FCZhG9Fz4SZlGSLJ_SlSuphR6nLG5e2QIaE-GHL7AfnZ5VwF2M0jkztC4Rk6f-NTFE72ZaUBv2WPZsPmEACcVE5ncwr7j-HpSdFTMDpx-7CXhws4rDer4iP8j7QSH4Q8JWhM29eBcbs9USrH_yQ0NM0yfkvFQL8GAMuFktwAHDq9nUG4mDws_YisIDNjnnt6IZwYZ0E0JZOQRd8yK94cfkubZs7WXp-LiCF_jj_EHxTMIA1HMADFvr80OSKXY6lzHjTFvSqXo9z-NNtc5sAVkYI7wOjkKJPyVPJ2rLf4pQpW_SyjH3qUZ3zinq0Qzk9GDlSNFYBQZA4sojcFSNpajLvQxKmEv_xwZQeaHaxXJ-vvo4fgee2pfd-_R_FYVzPN11Mx95bMi_gr6GAFmO5lLy9eh_46JPtrKkUQIvPJDH8'
                ]
            ]);

            $tweets = collect(json_decode($response->getBody()));
        }
        return view('home')->with([
            'tweets' => $tweets
        ]);
    }
}
