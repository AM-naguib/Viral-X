<?php

namespace App\Http\Controllers;
use App\Jobs\PostSitesData;
use App\Models\AccessToken;


class PostScrapingData extends Controller
{

    public function index()
    {
        $user_id = auth()->user()->id;
        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first();
        PostSitesData::dispatch($accessToken,$user_id);
    }


}
