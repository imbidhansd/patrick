<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class State extends Model {

    use LogsActivity;

    protected $fillable = [
        'name', 'short_name'
    ];
    protected $table = 'states';
    protected $dates = [
        'date',
    ];
    public $searchColumns = [
        'all' => 'All',
        'states.name' => 'Name',
        'states.name' => 'Short Name',
    ];
    protected static $logAttributes = [
        'name', 'short_name'
    ];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.name', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.id', '>', 0);
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
