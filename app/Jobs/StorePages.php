<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Models\AccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StorePages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user_id;
    public function __construct($id)
    {
        $this->user_id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $accessToken = AccessToken::where("user_id", $this->user_id)->where("type", "facebook")->first();

        $url = "https://graph.facebook.com/v12.0/me/accounts?access_token=$accessToken->token";

        $this->storePages($url);

    }
    public function storePages($url)
    {
        $data = $this->makeRequest($url);
        $pages = $data['data'];

        foreach ($pages as $page) {
            $nPage = new FbPage();
            $nPage->name = $page["name"];
            $nPage->page_id = $page["id"];
            $nPage->user_id = $this->user_id;
            $nPage->access_token = $page["access_token"];
            $nPage->save();
        }
        if (isset($data['paging']['next'])) {
            $next = $data['paging']['next'];
            $this->storePages($next);
        }
    }
    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
    }
}
