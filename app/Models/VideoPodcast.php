<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class VideoPodcast extends Model {

    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'trade_id', 'service_category_type_id', 'top_level_category_id', 'main_category_id',
        'title', 'slug',
        'embed_url', 'podcast_url', 'vimeo_url',
        'tags',
        'media_id',
        'description', 'content',
        'zipcode', 'mile_range',
        'index', 'follow', 'status'
    ];
    protected $table = 'video_podcasts';
    public $searchColumns = [
        'all' => 'All',
        'video_podcasts.title' => 'Title',
    ];
    protected static $logAttributes = [
        'trade_id', 'service_category_type_id', 'top_level_category_id', 'main_category_id',
        'title', 'slug',
        'embed_url', 'podcast_url', 'vimeo_url',
        'tags',
        'media_id',
        'description', 'content',
        'zipcode', 'mile_range',
        'index', 'follow', 'status'
    ];

    /* Foreign References */

    public function video_podcast_company() {
        return $this->hasMany('\App\Models\VideoPodcastCompany', 'video_podcast_id', 'id');
    }

    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function service_category_type() {
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function top_level_category() {
        return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    /* scopes */

    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'publish');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
