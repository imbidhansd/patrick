<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class TopLevelCategory extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable() : array{
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'old_id',
        'title', 'slug', 'tlc_id',
        'top_search_status', 'top_search_image', 'top_search_sort_order',
        'status'
    ];
    protected $table = 'top_level_categories';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'top_level_categories.title' => 'Title',
        'top_level_categories.tlc_id' => 'TLC ID',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'tlc_id',
        'top_search_status', 'top_search_image', 'top_search_sort_order',
        'status'
    ];
    
    // Foreign Ref.
    protected $cloneable_relations = ['top_level_category_trades'];

    public function top_level_category_trades() {
        return $this->hasMany('App\Models\TopLevelCategoryTrade', 'top_level_category_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->with('top_level_category_trades.trade');
        $query->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', $this->table . '.id');
        $query->groupBy('top_level_categories.id');
        $query->select(['top_level_categories.*']);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
