<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceCategory extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'tags', 'service_category_id',
        'abbr',
        'top_level_category_id', 'main_category_id',
        'service_category_type_id',
        'ppl_price',
        'networx_id', 'networx_task_id',
        'status', 'sort_order', 'sc_code'
    ];
    protected $table = 'service_categories';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'service_categories.title' => 'Title',
        'service_categories.abbr' => 'Abbreviation',
        'service_categories.sc_code' => 'SC ID',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'service_category_id',
        'abbr',
        'top_level_category_id', 'main_category_id',
        'service_category_type_id',
        'ppl_price',
        'networx_id', 'networx_task_id',
        'status', 'sort_order',
    ];
    // Foreign Ref.
    protected $cloneable_relations = ['service_category_main_categories'];

    public function top_level_category() {
        return $this->belongsTo('App\Models\TopLevelCategory', 'top_level_category_id', 'id')->withDefault(['title' => '']);
    }

    public function main_category() {
        return $this->belongsTo('App\Models\MainCategory', 'main_category_id', 'id')->withDefault(['title' => '']);
    }

    public function service_category_type() {
        return $this->belongsTo('App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->withDefault(['title' => '']);
    }

    public function networx_task() {
        return $this->belongsTo('\App\Models\NetworxTask', 'networx_id', 'id');
    }

    public function service_category_main_categories() {
        return $this->hasMany('App\Models\ServiceCategoryMainCategory');
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

        //$query->with('service_category_main_categories.main_category');
        //$query->leftJoin('service_category_main_categories', 'service_category_main_categories.service_category_id', '=', $this->table . '.id');
        $query->groupBy('service_categories.id');
        $query->select(['service_categories.*']);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        return $query->paginate($record_per_page);
    }

    public function getAdminNetworxList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        //$query->with('service_category_main_categories.main_category');
        //$query->leftJoin('service_category_main_categories', 'service_category_main_categories.service_category_id', '=', $this->table . '.id');
        $query->leftJoin('top_level_category_trades', $this->table . '.top_level_category_id', 'top_level_category_trades.top_level_category_id')
                ->where('top_level_category_trades.trade_id', '1')
                ->groupBy($this->table . '.id')
                ->select($this->table . '.*');

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        return $query->paginate($record_per_page);
    }

    public function generateServiceCategoryId($serviceCategoryObj) {
        $service_category_id = $serviceCategoryObj->top_level_category->tlc_id . '-' . $serviceCategoryObj->main_category_id;

        if ($serviceCategoryObj->service_category_type_id == 1) {
            $service_category_id .= 'R';
        } else if ($serviceCategoryObj->service_category_type_id == 2) {
            $service_category_id .= 'C';
        } else if ($serviceCategoryObj->service_category_type_id == 3) {
            $service_category_id .= 'N';
        }

        $counter = self::where([
                    ['top_level_category_id', $serviceCategoryObj->top_level_category_id],
                    ['service_category_type_id', $serviceCategoryObj->service_category_type_id],
                    ['main_category_id', $serviceCategoryObj->main_category_id]
                ])
                ->count();
        $service_category_id .= '-' . (($counter != 0) ? $counter : 1);

        return $service_category_id;
    }

}
