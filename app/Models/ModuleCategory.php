<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ModuleCategory extends Model {

    //
    use LogsActivity;

    protected $fillable = [
        'title', 'sort_order'
    ];
    protected $table = 'module_categories';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'module_categories.title' => 'Title',
    ];
    protected static $logAttributes = ['title', 'sort_order'];

    // Foreign Ref.
    public function modules() {
        return $this->hasMany('App\Models\Module');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where('id', '>', 0);
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
