<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class News extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'date',
        'media_id', 'banner_id',
        'short_content', 'content',
        'meta_title', 'meta_keywords', 'meta_description',
        'status', 'show_on_homepage'
    ];
    protected $table = 'news';
    protected $dates = ['date'];
    public $searchColumns = [
        'all' => 'All',
        'news.title' => 'Title',
    ];

    // Foreign Ref.
    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    public function banner() {
        return $this->belongsTo('App\Models\Media', 'banner_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.date', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    /* date fields */

    public function setDateAttribute($input) {
        if ($input != '') {
            $this->attributes['date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $input)->format(env('DB_DATE_FORMAT'));
        }
    }

    public function getDateAttribute($input) {
        if ($input != '') {
            return \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT'), $input)->format(env('DATE_FORMAT'));
        }
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
