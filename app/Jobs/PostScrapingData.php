<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Models\History;
use App\Models\SiteData;
use App\Models\AccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostScrapingData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $page;
    public $user_token;
    public $content;


    public function __construct($p, $u, $c)
    {
        $this->page = $p;
        $this->user_token = $u;
        $this->content = $c;

    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $page_id = $this->page->page_id;
        $page_token = $this->getPageAccessToken($page_id, $this->user_token);
        sleep(rand(10,20));

        $this->postContentToPage($page_id, $this->content, $page_token["access_token"]);


    }
    public function getPageAccessToken($page_id, $user_token)
    {
        $url = "https://graph.facebook.com/v12.0/$page_id?fields=access_token&access_token=$user_token";
        return Http::get($url)->json();
    }
    public function postContentToPage($page, $content, $token)
    {
        $postResponse = Http::post("https://graph.facebook.com/$page/feed", [
            'message' => $content,
            'access_token' => $token,
            'published' => true,
        ])->json();

        // Save In History0
        $this->saveHistory($content, $postResponse["id"], $this->page->user_id);
        return $postResponse;
    }
    public function saveHistory($content, $postId, $user_id)
    {
        $history = new History;
        $history->content = $content;
        $history->type = "Sites Auto Post";
        $history->user_id = $user_id;
        $history->post_link = "https://www.facebook.com/" . $postId;
        $history->save();

    }
}
