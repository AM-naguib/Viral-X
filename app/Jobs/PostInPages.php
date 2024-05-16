<?php

namespace App\Jobs;

use App\Models\History;
use App\Models\AccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostInPages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $user_id;
    public $pages;
    public $imageLink;
    public $content;

    public function __construct($u, $p, $i, $c)
    {
        $this->user_id = $u;
        $this->pages = $p;
        $this->imageLink = $i;
        $this->content = $c;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $accountToken = $this->getAccountToken($this->user_id);

        // $pagesTokens = $this->getPagesToken($this->pages, $accountToken);


        // $successPosts = $this->makePost($pagesTokens, $this->content, $this->imageLink);
        // $this->saveHistory($successPosts, $this->content, $this->user_id);

        Log::info("job done");

    }
    public function getAccountToken($user_id)
    {
        $accessToken = AccessToken::where("user_id", $user_id)->where("type", "facebook")->first();
        return $accessToken->token;
    }


    public function getPagesToken($ids, $token)
    {
        $pagesWithTokens = [];
        foreach ($ids as $id) {
            $url = "https://graph.facebook.com/v12.0/$id?fields=access_token&access_token=$token";
            $res = $this->makeRequest($url);
            Log::info($res);

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
                Log::info($photoPath);
                // $photoId = $this->photoUpload($id, $token, $photoPath);
                // $postResponse = Http::post("https://graph.facebook.com/$id/feed", [
                //     'message' => $message,
                //     'access_token' => $token,
                //     'attached_media' => json_encode([['media_fbid' => $photoId]]),
                //     'published' => true,
                // ]);
                // $success[] = $postResponse->json()['id'];

            }
        }
        return $success;
    }




    public function saveHistory($posts, $content, $user_id)
    {

        foreach ($posts as $post) {
            History::create([
                "user_id" => $user_id,
                "type" => "FaceBook Page",
                "content" => $content,
                "post_link" => "https://facebook.com/" . $post
            ]);
        }

    }
    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
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
