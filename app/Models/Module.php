<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Module extends Model {

    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    //
    protected $fillable = [
        'module_category_id',
        'title', 'name', 'permissions', 'permission_options',
        'sort_order'
    ];
    protected $table = 'modules';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'modules.title' => 'Title',
        'modules.name' => 'Name',
        'module_categories.title' => 'Category',
    ];
    protected static $logAttributes = ['module_category_id', 'title', 'name', 'terms_content', 'permissions', 'sort_order'];

    // Foreign Ref.
    public function module_category() {
        return $this->belongsTo('App\Models\ModuleCategory', 'module_category_id', 'id');
    }

    public function permissions() {
        return $this->hasMany('Spatie\Permission\Models\Permission');
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
