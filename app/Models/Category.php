<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model {

    //
    use Sluggable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug',
        'sub_title', 'date',
        'media_id', 'banner_id', 'banner_text',
        'meta_title', 'meta_keywords', 'meta_description',
        'short_content', 'content',
        'status', 'sort_order',
    ];
    protected $table = 'categories';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'categories.title' => 'Title',
        'categories.sub_title' => 'Sub Title',
        'categories.short_content' => 'Short Content',
        'categories.content' => 'Content',
    ];

    // Foreign Reference
    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    public function banner() {
        return $this->belongsTo('App\Models\Media', 'banner_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    // Date Fields
    /* public function setDateAttribute($input) {
      if ($input != '') {
      $this->attributes['date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $input)->format(env('DB_DATE_FORMAT'));
      }
      }

      public function getDateAttribute($input) {
      if ($input != '') {
      return \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT'), $input)->format(env('DATE_FORMAT'));
      }
      } */

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
