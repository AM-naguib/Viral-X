<?php

namespace App\Console\Commands;

use Exception;
use Goutte\Client;
use App\Models\SiteData;
use App\Models\UserSite;
use Illuminate\Console\Command;

class ScrapSitesDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScrapSitesData:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all posts from first page in each site';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = "";
        $sites = UserSite::all();
        foreach ($sites as $site) {
            if (isset($site->post_title_selector) && isset($site->post_link_selector) && isset($site->site_link)) {
                $res = $this->makeRequest($site->site_link);
                if ($res == false) {
                    continue;
                }
                $user_id=$site->user_id;
                $data = $this->getData($res, $site->post_title_selector, $site->post_link_selector);
                $this->saveInDb($data, $site->id, $user_id);
            } else {
                continue;
            }
        }
    }
    public function makeRequest($url)
    {
        $client = new Client();
        try {
            $res = $client->request("GET", $url);
            return $res;
        } catch (Exception $e) {

            return false;
        }
    }
    public function getData($res, $post_title_selector, $post_url_selector)
    {
        $result = [];
        $res->filter($post_title_selector)->each(function ($titleNode, $i) use ($res, $post_url_selector, &$result) {
            $urlNode = $res->filter($post_url_selector)->eq($i);
            $title = $titleNode->text();
            $url = $urlNode->attr("href");
            $result[] = ['title' => $title, 'url' => $url];
        });
        return $result;
    }

    function saveInDb($data, $site_id, $user_id)
    {

        foreach ($data as $value) {
            $existingRecord = SiteData::where('post_url', $value['url'])->first();
            if (!$existingRecord) {
                $siteData = SiteData::firstOrCreate([
                    'user_id' => $user_id,
                    'post_title' => $value['title'],
                    'post_url' => $value['url'],
                    'site_id' => $site_id,
                ], ['status' => 'waiting']);
            }
        }
    }
}
