<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Affiliate extends Model{
	use LogsActivity;
	
    protected $fillable = [
       'affiliate_name',
        'note',
        'api_key', 'api_secret',
        'status',
        'domain',
        'domain_abbr',
        'aweber_access_token',
        'aweber_refresh_token',
        'aweber_account_id',
        'aweber_enabled',
        'trade_id',
        'service_category_type_id',
        'top_level_categories',
        'aweber_member_list',
        'member_base_url'
    ];
    protected $table = 'affiliates';
    public $searchColumns = [
        'all' => 'All',
        'affiliates.affiliate_name' => 'Affiliate Name',
    ];

    protected static $logAttributes = [
    	'affiliate_name',
        'note',
        'api_key', 'api_secret',
        'status',
        'domain',
        'domain_abbr',
        'aweber_access_token',
        'aweber_refresh_token',
        'aweber_account_id',
        'aweber_enabled',
        'trade_id',
        'service_category_type_id',
        'top_level_categories',
        'aweber_member_list',
        'member_base_url'
    ];

    // Scopes
    public function scopeOrder($query){
        return $query->orderBy($this->table.'.id', 'DESC');
    }

    public function scopeActive($query){
        return $query->where($this->table.'.status', 'active');
    }

    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->withDefault(['title' => '']);
    }
    
    public function main_category_list() {
        return $this->hasMany('\App\Models\AffiliateMainCategory', 'affiliate_id', 'id')->with('service_category_type','main_category');
    }

    public function getAdminList($params){
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }
}
