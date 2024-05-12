<?php

namespace App\Jobs;

use App\Models\History;
use App\Models\AccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostInTwitter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user_id;
    public $accounts;
    public $content;
    public $imagePath;

    public function __construct($u,$a, $c, $i)
    {
        $this->user_id = $u;
        $this->accounts = $a;
        $this->content = $c;
        $this->imagePath = $i;
        Log::info($i);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tokens = $this->getTokens($this->accounts);
        $successPosts = $this->makePost($tokens, $this->content, $this->imagePath);
        $this->saveHistory($successPosts, $this->content);
    }
    public function getTokens($accounts)
    {
        $tokens = AccessToken::where("user_id", $this->user_id)->where("type", "twitter")->whereIn("id", $accounts)->select("name", "token", "token_secret")->get();
        return $tokens;
    }

    public function makePost($tokens, $message, $imagePath)
    {
        $consumerKey = env('TWITTER_CLIENT_ID');
        $consumerSecret = env('TWITTER_CLIENT_SECRET');
        $success = [];
        foreach ($tokens as $token) {
            $twitterAuth = new TwitterOAuth($consumerKey, $consumerSecret, $token->token, $token->token_secret);
            if ($imagePath == null) {
                $postParams = [
                    'text' => "$message"
                ];
            } else {
                $image = [];
                $twitterAuth->setApiVersion("1.1");
                $media = $twitterAuth->upload('media/upload', ['media' => $imagePath]);
                $image[] = $media->media_id_string;
                $twitterAuth->setApiVersion("2");
                $postParams = [
                    'text' => "$message",
                    'media' => ['media_ids' => $image]
                ];
            }


            $response = $twitterAuth->post('tweets', $postParams);
            if (isset($response->data->id)) {
                $success[] = ["post_id" => $response->data->id, "user_name" => $token->name];
            }
        }
        return $success;

    }

    public function saveHistory($successPosts, $content)
    {
        foreach ($successPosts as $post) {
            History::create([
                "user_id" => $this->user_id,
                "type" => "Twitter",
                "content" => $content,
                "post_link" => "https://twitter.com/" . $post["user_name"] . "/status/" . $post["post_id"]
            ]);
        }
    }
}
