<?php

namespace App\Http\Controllers;
use App\Models\Api; 
use App\Models\Url; 
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiController extends Controller
{
   
    public function shortner()
    {
        return view('shortner');

    }
    public function shortenUrl(Request $request)
    {

        $originalUrl = $request->input('url');
        $shortUrl = $request->input('shortUrl');
 

        $url = new Url();
        $url->orignal_url = $originalUrl;
        $url->short_url = $shortUrl;
        $url->save();

        return response()->json(['shortUrl' => $shortUrl]);
    }

    public function redirectToOriginalUrl($shortCode)
    {
        $url = Url::where('short_url', $shortCode)->first();
   
        if ($url) {
            return redirect($url->orignal_url);
        } else {
            return redirect('https://www.google.com/');
        }
    }

}
