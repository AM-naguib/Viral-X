<?php

namespace App\Console\Commands;

use App\Models\AccessToken;
use Illuminate\Support\Str;
use App\Models\ScheduledPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SchedulerPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish scheduler posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $post = ScheduledPost::where("scheduled_at", "<=", now())->where("status", "wating")->get();
        foreach ($post as $value) {
            $pages = $value->fbPages;
            foreach ($pages as $page) {
                $token = $this->getPageAccessToken($page->page_id, $page->user_id);
                $imageUrl = $value->image_url;
                if(Str::length($imageUrl)> 0 ){
                    $imageUrl = env("APP_URL")."/public/". $imageUrl;
                }else{
                    $imageUrl = null;
                }
                Log::info($imageUrl."nego");
                $this->publishPost($imageUrl, $value->content, $token, $page->page_id);
            }
            $value->status = "done";
            $value->save();


        }



    }


    public function getPageAccessToken($page_id, $user_id)
    {
        $token = AccessToken::where("user_id", $user_id)->where("type", "facebook")->first();

        $url = "https://graph.facebook.com/v12.0/$page_id?fields=access_token&access_token=$token->token";
        $res = $this->makeRequest($url);


        return $res["access_token"];
    }

    public function makeRequest($url)
    {
        $response = Http::get($url);
        return $response->json();
    }
    public function publishPost($imageUrl, $content, $token, $page_id)
    {
        $errors = [];
        $success = [];


        // upload photo without posting it
        Log::info($imageUrl."nego4");
        if ($imageUrl == null) {
            $postResponse = Http::post("https://graph.facebook.com/$page_id/feed", [
                'message' => $content,
                'access_token' => $token,
                'published' => true,
            ]);
            $success[] = $postResponse->json()['id'];
        } else {
            $photoId = $this->photoUpload($page_id, $token, $imageUrl);
            $postResponse = Http::post("https://graph.facebook.com/$page_id/feed", [
                'message' => $content,
                'access_token' => $token,
                'attached_media' => json_encode([['media_fbid' => $photoId]]),
                'published' => true,
            ]);
            $success[] = $postResponse->json()['id'];

        }

        return $success;
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
