<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'content', 'image_url', 'postTime','scheduled_at'];

    public function fbPages(){
        return $this->belongsToMany(FbPage::class, 'scheduled_post_fb_page');
    }
}
