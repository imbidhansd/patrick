<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserRole extends Model {

    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'title', 'admin_access',
        'status', 'sort_order',
    ];
    protected $table = 'user_roles';
    public $searchColumns = [
        'all' => 'All',
        'user_roles.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'admin_access',
        'status', 'sort_order',
    ];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->where('title', '!=', 'Super Admin');
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
