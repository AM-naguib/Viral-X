<?php

namespace App\Console\Commands;

use App\Models\FbPage;
use App\Models\History;
use App\Models\SiteData;
use App\Models\UserSite;
use App\Models\AccessToken;
use App\Jobs\PostScrapingData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class PostScrapingDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PostData:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish data in social media';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $sites = UserSite::select("id")->get();
        foreach ($sites as $site) {
            $user_token = "";
            $pages = DB::table("fb_page_user_site")->where("user_site_id", $site->id)->get();
            $data = SiteData::where("site_id", $site->id)->where("status", "waiting")->get();
            if (count($data) == 0) {
                continue;
            }
            foreach ($data as $value) {
                foreach ($pages as $page) {
                    $page_id = $page->fb_page_id;
                    $page = FbPage::find($page_id);
                    if (empty($user_token)) {
                        $user_token = AccessToken::where("user_id", $page->user_id)->where("type", "facebook")->first();
                        $user_token = $user_token->token;
                    }
                    PostScrapingData::dispatch($page, $user_token, $value->post_title . "\n" . $value->post_url);
                }
                $value->status = "done";
                $value->save();
            }

        }
    }
   
}
