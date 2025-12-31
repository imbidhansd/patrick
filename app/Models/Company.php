<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Custom;

class Company extends Authenticatable {

    use Sluggable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'company_name']];
    }

    protected $guard = 'company';
    protected $fillable = [
        'sales_representative_id',
        'membership_level_id', 'ownership_type', 'number_of_owners',
        'company_name', 'slug', 'public_company_name', 'short_company_name',
        'company_website',
        'main_company_telephone', 'secondary_telephone', 'company_mailing_address',
        'suite', 'city', 'county', 'state_id', 'zipcode',
        'in_home_service',
        'trade_id', 'main_category_id', 'secondary_main_category_id', 'category_reference',
        'include_rest_categories',
        'main_zipcode', 'main_zipcode_city', 'mile_range', 'allow_100_miles',
        'status',
        'credit_check_report_id', 'online_reputation_report_id',
        'activation_key', 'activated_at',
        'registered_date', 'approval_date', 'renewal_date', 'bg_check_date',
        'owner_name', 'owner_email',
        'internal_contact_name', 'internal_contact_email', 'internal_contact_phone',
        'company_logo_id',
        'company_page_media_id',
        'company_bio',
        'company_subscribe_status',
        'regarding_your_request', 'special_offers', 'scams_updates', 'general_updates',
        'why_unsubscribe', 'unsubscribe_reason',
        'leads_status', 'lead_pause_date', 'lead_resume_date',
        'permanent_budget', 'temporary_budget',
        'package_id', 'package_code',
        'subscription_id',
        'awards', 'is_founding_member',
        'allow_to_edit',
        'facebook_url', 'linkedin_url', 'twitter_url', 'created_by'
    ];
    protected $table = 'companies';
    public $searchColumns = [
        'all' => 'All',
        'companies.company_name' => 'Company Name',
        'company_users.email' => 'Email',
        'companies.company_website' => 'Company Website',
        'companies.main_company_telephone' => 'Main Telephone',
        'companies.secondary_telephone' => 'Secondary Telephone',
        'companies.city' => 'City',
        'companies.county' => 'County',
        'states.name' => 'State',
        'companies.main_zipcode' => 'Zipcode',
            /* 'companies.main_zipcode' => 'Main Zipcode',
              'companies.mile_range' => 'Mile Range',
              'company_zipcodes.zip_code' => 'Other Zipcodes', */
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthPassword() {
        return $this->password;
    }

    // Foreign References
    public function sales_representative() {
        return $this->belongsTo('\App\Models\User', 'sales_representative_id', 'id')->active()->withDefault([
                    'first_name' => '',
                    'last_name' => ''
        ]);
    }

    public function membership_level() {
        return $this->belongsTo('\App\Models\MembershipLevel', 'membership_level_id', 'id')->withDefault(['title' => '']);
    }

    public function package() {
        return $this->belongsTo('\App\Models\Package', 'package_id', 'id');
    }

    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->withDefault(['title' => '']);
    }

    public function main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->withDefault(['title' => '']);
    }

    public function secondary_main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'secondary_main_category_id', 'id')->withDefault(['title' => '']);
    }

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

    public function company_logo() {
        return $this->belongsTo('\App\Models\Media', 'company_logo_id', 'id');
    }

    public function company_page_media() {
        return $this->belongsTo('\App\Models\Media', 'company_page_media_id', 'id');
    }

    public function service_category() {
        return $this->hasMany('\App\Models\CompanyServiceCategory', 'company_id', 'id')->with('main_category')->active();
    }

    public function company_application() {
        return $this->hasMany('\App\Models\CompanyApplication', 'company_id', 'id');
    }

    public function company_zipcodes() {
        return $this->hasMany('\App\Models\CompanyZipcode', 'company_id', 'id');
    }

    public function company_information() {
        return $this->hasOne('\App\Models\CompanyInformation', 'company_id', 'id')->with([
                    'company_owner1',
                    'company_owner2',
                    'company_owner3',
                    'company_owner4'
        ]);
    }

    public function ppl_company_information() {
        return $this->hasOne('\App\Models\CompanyInformation', 'company_id', 'id');
    }

    public function company_lead_notification() {
        return $this->hasOne('\App\Models\CompanyLeadNotification', 'company_id', 'id');
    }

    public function company_approval_status() {
        return $this->hasOne('\App\Models\CompanyApprovalStatus', 'company_id', 'id');
    }

    public function company_licensing() {
        return $this->hasOne('\App\Models\CompanyLicensing', 'company_id', 'id')->with([
                    'proof_of_ownership_file',
                    'state_business_registeration_file',
                    'state_licensed_file',
                    'country_licensed_file',
                    'city_licensed_file',
                    'written_warrenty_file',
                    'pre_screening_report_file'
        ]);
    }

    public function company_customer_references() {
        return $this->hasOne('\App\Models\CompanyCustomerReference', 'company_id', 'id')->with('customer_reference_file');
    }

    public function company_insurance() {
        return $this->hasOne('\App\Models\CompanyInsurance', 'company_id', 'id')->with(['liability_insurance_file', 'compensation_insurance_file']);
    }

    public function company_listing_agreement() {
        return $this->hasOne('\App\Models\CompanyListingAgreement', 'company_id', 'id');
    }

    public function company_users() {
        return $this->hasMany('\App\Models\CompanyUser', 'company_id', 'id')->with('media');
    }

    public function company_super_admin() {
        return $this->hasOne('\App\Models\CompanyUser', 'company_id', 'id')->where('company_user_type', 'company_super_admin');
    }

    public function company_users_approval_remaining() {
        return $this->hasMany('\App\Models\CompanyUser', 'company_id', 'id')->with('media')->remainingApproval();
    }

    public function profile_views() {
        return $this->hasMany('\App\Models\CompanyProfileView', 'company_id', 'id');
    }

    /* ppl company leads for cron start */

    public function ppl_company_leads() {
        $start_date = new Carbon('first day of last month');
        $last_date = new Carbon('last day of last month');

        /* $start_date = new Carbon('first day of July 2020');
          $last_date = new Carbon('last day of July 2020'); */

        return $this->hasMany('\App\Models\CompanyLead', 'company_id', 'id')->select('company_leads.*')
                        ->with('lead')
                        ->leftJoin('leads', 'company_leads.lead_id', 'leads.id')
                        ->where([
                            [DB::raw('DATE(company_leads.created_at)'), '>=', $start_date->format(env('DB_DATE_FORMAT'))],
                            [DB::raw('DATE(company_leads.created_at)'), '<=', $last_date->format(env('DB_DATE_FORMAT'))],
                            ['is_hidden', 'no']
                        ])
                        ->where(function ($query) {
                            $query->whereNull('leads.dispute_status');
                            $query->orWhere('leads.dispute_status', 'cancelled');
                        });
    }

    /* ppl company leads for cron end */

    public function credit_report_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'credit_check_report_id', 'id')->withDefault(['media' => '']);
    }

    public function online_reputation_report_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'online_reputation_report_id', 'id')->withDefault(['media' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'Active');
    }

    public function scopeLeadStatus($query, $status) {
        return $query->where($this->table . '.leads_status', $status);
    }

    public function showCompanyName() {
        if ($this->short_company_name != '') {
            return $this->short_company_name;
        } else {
            return $this->company_name;
        }
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);

        if (isset($params['search_field']) && $params['search_field'] == 'companies.main_zipcode') {
            $main_zipcode = $params['search_text'];
            $params['search_text'] = null;
        }

        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id');

        if (isset($params['search_field']) && $params['search_field'] == 'companies.main_zipcode') {
            $query->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id');

            if (isset($params['mile_range']) && $params['mile_range'] != '') {
                try {
                    $zipCodes = Custom::getZipCodeRange($main_zipcode, $params['mile_range']);
                    if (count($zipCodes) > 0) {
                        $query->where(function ($q) use ($main_zipcode, $zipCodes) {
                            $q->where('companies.main_zipcode', $main_zipcode);
                            $q->orWhereIn('company_zipcodes.zip_code', array_column($zipCodes, 'zip_code'));
                        });
                    }
                } catch (Exception $e) {
                    //return 'fail';
                }
            } else {
                $query->where(function ($q) use ($main_zipcode) {
                    $q->where('companies.main_zipcode', $main_zipcode);
                    $q->orWhere('company_zipcodes.zip_code', $main_zipcode);
                });
            }
        }


        $query->leftJoin('states', 'states.id', '=', 'companies.state_id');
        $query->leftJoin('company_users', 'companies.id', 'company_users.company_id')
                ->where('company_users.company_user_type', 'company_super_admin');


        if (isset($params['membership_level_id']) && $params['membership_level_id'] > 0 && $params['membership_level_id'] != 'paid_members' && $params['membership_level_id'] != 'unpaid_members') {
            $query->where('membership_level_id', $params['membership_level_id']);
        } else if (isset($params['membership_level_id']) && $params['membership_level_id'] == 'paid_members') {
           $query->leftJoin('membership_levels', $this->table . '.membership_level_id', 'membership_levels.id')->where('membership_levels.paid_members', 'yes');
        } else if (isset($params['membership_level_id']) && $params['membership_level_id'] == 'unpaid_members') {
            $query->leftJoin('membership_levels', $this->table . '.membership_level_id', 'membership_levels.id')->where('membership_levels.paid_members', 'no');
        }
        $query->groupBy('companies.id');

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select($this->table . '.*')->with(['sales_representative', 'membership_level', 'service_category', 'company_approval_status', 'company_users_approval_remaining']);

        //dd($query);
        return $query->paginate($record_per_page);
    }

    public function getPendingApprovalAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->with('membership_level')
                ->select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where([
                    ['membership_levels.paid_members', 'yes'],
                    ['companies.status', 'Pending Approval']
        ]);
        return $query->paginate($record_per_page);
    }

    public function getPaidPendingAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->with('membership_level')
                ->select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where([
                    ['membership_levels.paid_members', 'yes'],
                    ['companies.status', 'Paid Pending']
        ]);
        return $query->paginate($record_per_page);
    }

    public function getPendingCompanyGalleryAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->with('membership_level')
                ->select('companies.*')
                ->leftJoin('company_galleries', 'companies.id', 'company_galleries.company_id')
                ->where('company_galleries.status', 'pending')
                ->groupBy('companies.id');

        return $query->paginate($record_per_page);
    }

}
