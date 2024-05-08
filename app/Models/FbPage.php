<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    use HasFactory;



    public function user(){
        return $this->belongsTo(User::class);
    }


    public function userSites()
    {
        return $this->belongsToMany(UserSite::class);
    }

    public function accesstoken(){
        return $this->belongsTo(AccessToken::class);
    }
    public function scheduledPosts(){
        return $this->belongsToMany(ScheduledPost::class, 'scheduled_post_fb_page');
    }
}
