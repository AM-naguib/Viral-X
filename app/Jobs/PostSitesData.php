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

class PostSitesData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $token;
    public $user;
    public function __construct($accessToken, $user_id)
    {
        $this->token = $accessToken;
        $this->user = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ids = DB::table('fb_page_user_site')->get();
        $successData = [];
        foreach ($ids as $value) {
            $page = FbPage::find($value->fb_page_id);

            $data = SiteData::where("site_id", $value->user_site_id)->where("status", "waiting")->get();
            //
            if (count($data) == 0) {
                continue;
            }

            $pageToken = $this->getPageAccessToken($page->page_id);

            foreach ($data as $value) {
                $res = $this->postContentToPage($page->page_id, $value->post_title . "\n" . $value->post_url, $pageToken['access_token']);
                $successData[] = $value->id;
                $value->updated_at = now();
                $value->save();
            }

        }
        $successData = array_unique($successData);
        SiteData::whereIn("id", $successData)->update([
            "status" => "done",
        ]);
    }

    public function getPageAccessToken($page_id)
    {

        $accessToken = $this->token;


        $url = "https://graph.facebook.com/v12.0/$page_id?fields=access_token&access_token=$accessToken->token";

        return Http::get($url)->json();


    }

    public function postContentToPage($page_id, $content, $token)
    {
        $this->addDelay($page_id);
        $postResponse = Http::post("https://graph.facebook.com/$page_id/feed", [
            'message' => $content,
            'access_token' => $token,
            'published' => true,
        ])->json();

        // Save In History
        $this->saveHistory($content, $postResponse["id"]);
        return $postResponse;
    }

    public function saveHistory($content, $postId)
    {
        $history = new History;
        $history->content = $content;
        $history->type = "Sites Auto Post";
        $history->user_id = $this->user;
        $history->post_link = "https://www.facebook.com/" . $postId;
        $history->save();

    }

    public function addDelay($page_id)
    {
        $lastPage = History::where("type", "Sites Auto Post")->latest()->first();
        if ($lastPage != null) {
            $lastPage = explode("/", $lastPage->post_link)[3];
            $lastPage = explode("_", $lastPage)[0];
            if ($lastPage == $page_id) {
                sleep(rand(30, 60));
            }
        }
    }
}
