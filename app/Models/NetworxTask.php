<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NetworxTask extends Model {

    use LogsActivity;

    protected $fillable = [
        'networx_id', 'task_name', 'task_id'
    ];
    protected $table = 'networx_tasks';
    public $searchColumns = [
        'all' => 'All',
        'networx_tasks.networx_id' => 'Networx',
        'networx_tasks.task_name' => 'Task Name',
        'networx_tasks.task_id' => 'Task ID',
    ];
    protected static $logAttributes = [
        'networx_id', 'task_name', 'task_id'
    ];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }
    
}
