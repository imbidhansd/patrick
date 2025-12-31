<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model {

    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'package_code',
        'company_id', 'company_email',
        'membership_level_id', 'qty_of_owners',
        'trade_id', 'top_level_categories',
        'main_category_id', 'secondary_main_category_id',
        'service_categories', 'include_rest_categories',
        'ppl_monthly_budget',
        'bg_pre_screen_first_owner_fee', 'bg_pre_screen_other_owner_fee',
        'setup_fee', 'todays_total_fee',
        'membership_total_fee', 'leads_total_fee',
        'suggested_product_total_fee',
        'final_total_fee', 'addendum',
        'status'
    ];
    protected $table = 'packages';
    public $searchColumns = [
        'all' => 'All',
        'packages.title' => 'Title',
        'packages.package_code' => 'Package Code',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'package_code',
        'membership_level_id', 'qty_of_owners',
        'bg_pre_screen_fee', 'setup_fee', 'todays_total_fee',
        'membership_total_fee', 'leads_total_fee', 'suggested_product_total_fee',
        'final_total_fee',
        'status'
    ];

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function membership_level() {
        return $this->belongsTo('\App\Models\MembershipLevel', 'membership_level_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function package_service_category() {
        return $this->hasMany('\App\Models\PackageServiceCategory', 'package_id', 'id');
    }

    public function package_products() {
        return $this->hasMany('\App\Models\PackageProduct', 'package_id', 'id')->with('product');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    // getter-setter
    public function getBgPreScreenFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getSetupFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getTodaysTotalFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getMembershipTotalFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getLeadsTotalFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getSuggestedProductTotalFeeAttribute($value) {
        return number_format($value, 2, '.', '');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
