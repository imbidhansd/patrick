<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'media_id', 'content',
        'meta_title', 'meta_keywords', 'meta_description',
        'index', 'follow', 'status'
    ];
    protected $table = 'pages';
    public $searchColumns = [
        'all' => 'All',
        'pages.title' => 'Title',
    ];
    protected static $logAttributes = ['title', 'media_id', 'content', 'meta_title', 'meta_keywords', 'meta_description', 'index', 'follow', 'status'];

    // Foreign Ref.
    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('pages.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select(['pages.*']);
        return $query->paginate($record_per_page);
    }

}
