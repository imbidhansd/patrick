<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class MainCategory extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'old_id',
        'title', 'slug', 'tags', 'abbr',
        'image_link', 'media_id',
        'annual_price', 'monthly_price', 'ppl_price',
        'top_search_status', 'top_search_sort_order',
        'status',
    ];
    protected $table = 'main_categories';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'main_categories.title' => 'Title',
        'main_categories.abbr' => 'Abbreviation',
    ];
    protected static $logAttributes = [
        'old_id',
        'title', 'slug', 'tags', 'abbr',
        'image_link', 'media_id',
        'annual_price', 'monthly_price', 'ppl_price',
        'top_search_status', 'top_search_sort_order',
        'status',
    ];
    // Foreign Ref.
    protected $cloneable_relations = ['main_category_top_level_categories'];

    public function main_category_top_level_categories() {
        return $this->hasMany('App\Models\MainCategoryTopLevelCategory');
    }

    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->with('main_category_top_level_categories.top_level_category');
        $query->leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', $this->table . '.id');
        $query->groupBy('main_categories.id');
        $query->select(['main_categories.*']);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
