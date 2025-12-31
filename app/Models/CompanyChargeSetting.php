<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyChargeSetting extends Model {

    //
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'title', 'slug', 'amount', 'sort_order',
        'status',
    ];
    protected $table = 'company_charge_settings';
    public $searchColumns = [
        'all' => 'All',
        'company_charge_settings.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'amount', 'sort_order',
        'status',
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
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
