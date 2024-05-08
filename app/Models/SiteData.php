<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteData extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'post_title', 'post_url', 'status', 'site_id'];

    public function userSite(){
        return $this->belongsTo(UserSite::class);
    }
}
