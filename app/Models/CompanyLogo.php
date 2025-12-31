<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyLogo extends Model {

    use LogsActivity;

    protected $fillable = [
        'company_id', 'site_logo_id', 'url', 'unique_key', 'status'
    ];
    protected $table = 'company_logos';
    public $searchColumns = [
        'all' => 'All',
        'company_logos.unique_key' => 'Unique Key'
    ];
    protected static $logAttributes = [
        'company_id', 'site_logo_id', 'url', 'unique_key', 'status'
    ];

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function site_logo() {
        return $this->belongsTo('\App\Models\SiteLogo', 'site_logo_id', 'id')->with('media')->active()->withDefault(['title' => '']);
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

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
