<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model {

    use LogsActivity;

    protected $fillable = [
        'id',
        'lead_generate_for',
        'full_name', 'email', 'phone',
        'additional_phone', 'additional_phone_number',
        'trade_id', 'top_level_category_id', 'main_category_id', 'service_category_type_id', 'service_category_id',
        'timeframe', 'price',
        'project_address', 'state_id', 'city', 'zipcode',
        'content',
        'lead_activation_key', 'lead_activated', 'lead_active_date',
        'company_id',
        'dispute_status', 'dispute_content', 'dispute_decline_reason',
        'is_phone', 'no_of_phone', 'is_email', 'no_of_email',
        'follow_up_mail_category_id',
        'networx_code',
        'ad_tracking', 'additional_notes',
        'regarding_your_request', 'special_offers', 'scams_updates', 'general_updates',
        'why_unsubscribe', 'unsubscribe_reason',
        'subscribe', 'subscribe_at', 'unsubscribe_at',
        'ip_address', 'cert_url', 'affiliate_id',
        'signup_url','correlation_id','networx_redirect_url','company_slugs_csv'
    ];
    protected $table = 'leads';
    public $searchColumns = [
        'all' => 'All',
        'leads.full_name' => 'Consumer Name',
        'leads.email' => 'Consumer Email',
        'leads.phone' => 'Consumer Phone',
        'leads.project_address' => 'Project Address',
        'leads.city' => 'City',
        'leads.zipcode' => 'Zipcode',
    ];
    protected static $logAttributes = [
        'lead_generate_for',
        'full_name', 'email', 'phone',
        'additional_phone', 'additional_phone_number',
        'trade_id', 'top_level_category_id', 'main_category_id', 'service_category_type_id', 'service_category_id',
        'timeframe', 'price',
        'project_address', 'state_id', 'city', 'zipcode',
        'content',
        'lead_activation_key', 'lead_activated', 'lead_active_date',
        'company_id',
        'dispute_status', 'dispute_content', 'is_phone', 'no_of_phone', 'is_email', 'no_of_email',
        'follow_up_mail_category_id',
        'networx_code',
        'ad_tracking', 'additional_notes',
        'regarding_your_request', 'special_offers', 'scams_updates', 'general_updates',
        'why_unsubscribe', 'unsubscribe_reason',
        'subscribe', 'subscribe_at', 'unsubscribe_at',
        'ip_address','created_at','updated_at','correlation_id'
    ];

    // Foreign References
    public function top_level_category() {
        return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category() {
        return $this->belongsTo('\App\Models\ServiceCategory', 'service_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category_type() {
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

    public function company_lead() {
        return $this->hasMany('\App\Models\CompanyLead', 'lead_id', 'id')->with('company_detail');
    }

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->with('ppl_company_information');
    }

    public function lead_generate_for_company() {
        return $this->belongsTo('\App\Models\Company', 'lead_generate_for', 'id');
    }

    public function follow_up_mail_category() {
        return $this->belongsTo('\App\Models\FollowUpMailCategory', 'follow_up_mail_category_id', 'id');
    }

    public function affiliate() {
        return $this->belongsTo('\App\Models\Affiliate', 'affiliate_id', 'id');
    }

    public function lead_follow_up_emails() {
        return $this->hasMany('\App\Models\LeadFollowUpEmail', 'lead_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActivated($query) {
        return $query->where($this->table . '.lead_activated', 'yes');
    }

    public function scopeActivatedOrder($query) {
        return $query->orderBy($this->table . '.lead_active_date', 'ASC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table, 'lead_active_date');

        $query->leftJoin('companies', 'companies.id', '=', 'leads.company_id');
        //$query->select(['leads.*']);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->select([$this->table . '.*'])
        ->with(['state', 'main_category', 'service_category_type', 'service_category', 'company_lead', 'affiliate']);
        $query->orderBy('id', 'desc');
        return $query->paginate($record_per_page);
    }

    /* Subscriber list start */

    public function getSubscriberList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table, 'lead_active_date');

        $query->leftJoin('companies', 'companies.id', '=', 'leads.company_id');
        //$query->select(['leads.*']);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->select([$this->table . '.*'])->with(['state', 'lead_generate_for_company', 'lead_follow_up_emails']);
        return $query->paginate($record_per_page);
    }

    /* Subscriber list end */

    /* Open Dispute Leads start */

    public function getOpenDisputeAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->select([$this->table . '.*'])->with(['state', 'main_category', 'service_category_type', 'service_category', 'company_lead'])->where('dispute_status', 'in process')->whereNotNull('dispute_status');

        return $query->paginate($record_per_page);
    }

    /* Open Dispute Leads end */

    /* Closed Dispute Leads start */

    public function getClosedDisputeAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->select([$this->table . '.*'])->with(['state', 'main_category', 'service_category_type', 'service_category', 'company_lead'])->where('dispute_status', '!=', 'in process')->whereNotNull('dispute_status');
        return $query->paginate($record_per_page);
    }

    /* Closed Dispute Leads end */
}
