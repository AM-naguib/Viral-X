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

class PostInGroups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user_id;
    public $groups;
    public $content;
    public $imagePath;
    public $imageName;

    public function __construct($u, $g, $c, $i, $n)
    {
        $this->user_id = $u;
        $this->groups = $g;
        $this->content = $c;
        $this->imagePath = $i;
        $this->imageName = $n;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $gg = $this->groups;
        $token = $this->getAccountToken($this->user_id);
        $posts = $this->makePost($token, $gg, $this->content, $this->imagePath, $this->imageName);
        $this->saveHistory($posts, $this->content);
    }
    public function getAccountToken($user_id)
    {
        $accessToken = AccessToken::where("user_id", $user_id)->where("type", "facebook")->first()->token;
        return $accessToken;
    }

    public function makePost($token, $groups, $content, $imagePath, $imageName)
    {
        $success = [];
        foreach ($groups as $group) {
            if (!empty($imagePath)) {
                $postResponse = Http::attach(
                    'source',
                    file_get_contents($imagePath),
                    $imageName
                )->post("https://graph.facebook.com/{$group}/photos", [
                            'message' => $content,
                            'access_token' => $token,
                            'privacy' => json_encode(['value' => 'EVERYONE'])
                        ])->json();
            } else {
                $postResponse = Http::post("https://graph.facebook.com/{$group}/feed", [
                    'message' => $content,
                    'access_token' => $token,
                ])->json();
            }
            Log::info($postResponse);
            if (isset($postResponse["id"])) {
                $success[] = $postResponse["id"];
            }
        }

        return $success;
    }


    public function saveHistory($posts, $content)
    {

        foreach ($posts as $post) {
            History::create([
                "user_id" => $this->user_id,
                "type" => "FaceBook Group",
                "content" => $content,
                "post_link" => "https://facebook.com/" . $post
            ]);
        }

    }
}
