<?php

namespace App\Http\Controllers\Back;

use App\Jobs\PostInPages;
use App\Jobs\StorePages;
use App\Models\AccessToken;
use App\Models\FbPage;
use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FbPageController extends Controller
{

    public function index()
    {
        $pages = FbPage::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.pages-show', compact("pages"));
    }

    public function getPages()
    {
        $user_id = auth()->user()->id;
        FbPage::where("user_id", $user_id)->delete();
        StorePages::dispatch($user_id);
        return to_route('admin.social-accounts');

    }


    public function pagesSendPost(Request $request)
    {
        $imageLink = null;
        $request->validate([
            "content" => "required|string",
            "pages" => "required",
            "image" => "nullable|image|mimes:png,jpg,jpeg,webp"
        ]);

        if ($request->hasFile("image")) {
            $imageLink = $request->file("image")->store("public");
            $imageLink = asset("/public/" . $imageLink);
        }
        $user_id = auth()->user()->id;
        $pages = $request->pages;
        $content = $request->content;
        PostInPages::dispatch($user_id, $pages, $imageLink, $content);
        return redirect()->route("admin.history")->with("success", "Posts sent successfully");

    }


    public function getAccountToken()
    {

        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first();
        return $accessToken->token;
    }


    public function getPagesToken($ids, $token)
    {
        $pagesWithTokens = [];
        foreach ($ids as $id) {
            $url = "https://graph.facebook.com/v12.0/$id?fields=access_token&access_token=$token";
            $res = $this->makeRequest($url);

            $pagesWithTokens[$res["id"]] = $res['access_token'];
        }
        return $pagesWithTokens;
    }

    public function makePost($tokens, $message, $photoPath)
    {
        $errors = [];
        $success = [];
        foreach ($tokens as $id => $token) {

            // upload photo without posting it

            if ($photoPath == null) {
                $postResponse = Http::post("https://graph.facebook.com/$id/feed", [
                    'message' => $message,
                    'access_token' => $token,
                    'published' => true,
                ]);
                $success[] = $postResponse->json()['id'];
            } else {
                $photoUrl = "https://code-solutions.site/USED-Gift-Card.png";
                $photoId = $this->photoUpload($id, $token, $photoUrl);
                $postResponse = Http::post("https://graph.facebook.com/$id/feed", [
                    'message' => $message,
                    'access_token' => $token,
                    'attached_media' => json_encode([['media_fbid' => $photoId]]),
                    'published' => true,
                ]);
                $success[] = $postResponse->json()['id'];

            }
        }
        return $success;
    }




    public function saveHistory($posts, $content)
    {

        foreach ($posts as $post) {
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "FaceBook Page",
                "content" => $content,
                "post_link" => "https://facebook.com/" . $post
            ]);
        }

    }



    public function photoUpload($pageId, $token, $photoUrl)
    {
        $photoUploadResponse = Http::post("https://graph.facebook.com/$pageId/photos", [
            'access_token' => $token,
            'url' => $photoUrl,
            'published' => false,
        ]);
        return $photoUploadResponse->json()['id'];

    }
}


