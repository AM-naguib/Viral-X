<?php

namespace App\Http\Controllers\Back;

use App\Models\History;
use App\Jobs\PostInTwitter;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Storage;

class TwitterController extends Controller
{
    public function twitterSendPost(Request $request)
    {
        $imagePath = null;
        $request->validate([
            "content" => "required|string",
            "accounts" => "required|exists:access_tokens,id,user_id," . auth()->user()->id,
            "image" => "image|mimes:jpeg,png,jpg,gif,svg,webp"
        ]);
        $content = $request->content;
        $accounts = $request->accounts;
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("public");
            $imagePath = Storage::path($imagePath);

        }
        $user_id = auth()->user()->id;


        PostInTwitter::dispatch($user_id, $accounts, $content, $imagePath);
        return redirect()->route("admin.history")->with("success", "Posts Sent Successfully");
    }

}
