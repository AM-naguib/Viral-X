<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\FbGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function index()
    {
        $accounts = AccessToken::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.social-accounts', compact("accounts"));
    }



    public function provider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {


        $user = Socialite::driver($provider)->user();

        if ($provider == "facebook") {

            $token = $user->token;
            AccessToken::create([
                'token' => $token,
                "type" => $provider,
                "user_id" => auth()->user()->id,
                "name" => $user->name
            ]);
        } else {
            $token = $user->token;
            $token_secret = $user->tokenSecret;
            AccessToken::create([
                'token' => $token,
                'token_secret' => $token_secret,
                "type" => $provider,
                "user_id" => 1,
                "name" => $user->nickname
            ]);
        }
        return redirect()->route("admin.social-accounts")->with("success", "Account added successfully");
    }



    public function destroy($id){
        $accessToken = AccessToken::findOrFail($id);
        if(!$accessToken->user_id == auth()->user()->id){
            return redirect()->route("admin.social-accounts")->with("error", "Something went wrong. Please try again");
        }
        $accessToken->delete();
        return redirect()->route("admin.social-accounts")->with("success", "Account deleted successfully");
    }


}
