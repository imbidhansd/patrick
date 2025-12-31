<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model {

    //
    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'post_category_id',
        'title', 'slug', 'media_id', 'content',
        'meta_title', 'meta_keywords', 'meta_description',
        'status', 'comment_status',
    ];
    protected $table = 'posts';
    public $searchColumns = [
        'all' => 'All',
        'posts.title' => 'Title',
    ];
    protected static $logAttributes = ['post_category_id', 'title', 'media_id', 'content', 'meta_title', 'meta_keywords', 'meta_description', 'status', 'comment_status'];

    // Foreign Ref.
    public function post_category() {
        return $this->belongsTo('App\Models\PostCategory', 'post_category_id', 'id');
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('posts.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->leftJoin('post_categories', 'post_categories.id', '=', 'posts.post_category_id');
        $query->with(['post_category']);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select(['posts.*']);
        return $query->paginate($record_per_page);
    }

}
