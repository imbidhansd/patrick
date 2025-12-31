<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class SiteLogo extends Model {

    use LogsActivity;

    protected $fillable = [
        'title', 'size', 'banner_for', 'banner_url', 'media_type', 'media_id', 'status', 'sort_order', 'domain_slug', 'banner_alt'
    ];
    protected $table = 'site_logos';
    public $searchColumns = [
        'all' => 'All',
        'site_logos.title' => 'Title'
    ];
    protected static $logAttributes = [
        'title', 'size', 'media_type', 'media_id', 'status', 'sort_order'
    ];

    // Foreign Ref.
    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    public function company_logo() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        return $this->hasOne('\App\Models\CompanyLogo', 'site_logo_id', 'id')->where('company_id', $company_id);
    }

    public function company_banner() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        return $this->hasOne('\App\Models\CompanyLogo', 'site_logo_id', 'id')->where('company_id', $company_id);
    }

    // Scopes
    public function scopeOrder($query) {
        $query->orderBy($this->table . '.banner_for', 'ASC');
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function scopeMediaBanner($query) {
        return $query->where($this->table . '.media_type', 'banner');
    }

    public function scopeMediaLogo($query) {
        return $query->where($this->table . '.media_type', 'logo');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
               
        if (!empty($params['domain_slug'])) {
            $query->where('domain_slug', $params['domain_slug']);
        }
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->with('media')->paginate($record_per_page);
    }
}
