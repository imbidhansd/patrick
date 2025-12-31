<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoPodcastCompany extends Model {

    protected $fillable = [
        'video_podcast_id', 'company_id'
    ];
    protected $table = 'video_podcast_companies';

    /* Foreign References */

    public function video_podcast() {
        return $this->belongsTo('\App\Models\VideoPodcast', 'video_podcast_id', 'id')->active();
    }

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->active();
    }

}
