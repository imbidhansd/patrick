<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceCategoryType extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'trade_id',
        'title', 'slug', 'abbr',
        'media_id',
        'status', 'sort_order',
    ];
    protected $table = 'service_category_types';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'service_category_types.title' => 'Title',
    ];
    protected static $logAttributes = [
        'trade_id',
        'title', 'abbr',
        'media_id',
        'status', 'sort_order',
    ];

    /* Foreign References */

    public function trade() {
        return $this->belogsTo('\App\Models\Trade', 'trade_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
