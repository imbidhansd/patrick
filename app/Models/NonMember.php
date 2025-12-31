<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Custom;
class NonMember extends Model {

    protected $fillable = [
        'first_name', 'last_name', 'company_name', 'email', 'phone',
        'address', 'city', 'state_id', 'zipcode', 'mile_range',
        'trade_id', 'service_category_type_id', 'top_level_categories',
        'how_did_you_hear_about_us',
        'comments',
        'activation_key', 'activation_date',
        'status',
        'subscribe_status', 'why_unsubscribe', 'unsubscribe_reason','created_by'
    ];
    protected $table = 'non_members';
    public $searchColumns = [
        'all' => 'All',
        'non_members.first_name' => 'First Name',
        'non_members.last_name' => 'Last Name',
        'non_members.company_name' => 'Company Name',
        'non_members.email' => 'Email',
        'non_members.phone' => 'Phone',
        'non_members.zipcode' => 'Zipcode',
    ];

    // Foreign References
    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->withDefault(['title' => '']);
    }

    public function service_category_type() {
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->withDefault(['title' => '']);
    }

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

    public function top_level_category_list() {
        return $this->hasMany('\App\Models\NonMemberTopLevelCategory', 'non_member_id', 'id')->with('top_level_category');
    }

    public function zipcode_list() {
        return $this->hasMany('\App\Models\NonMemberZipcode', 'non_member_id', 'id');
    }

    // Scopes
    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        if (isset($params['mile_range']) && $params['mile_range'] != '') {
            $zipcode = $params['search_text'];
            $params['search_text'] = null;
        }
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        

        if (isset($params['mile_range']) && $params['mile_range'] != '') {
            try {
                $zipCodes = Custom::getZipCodeRange($zipcode, $params['mile_range']);
                if (count($zipCodes) > 0) {
                    $query->leftJoin('non_member_zipcodes', $this->table . '.id', 'non_member_zipcodes.non_member_id');
                    $query->where(function ($q) use ($zipcode, $zipCodes) {
                        $q->where($this->table . '.zipcode', $zipcode);
                        $q->orWhereIn('non_member_zipcodes.zipcode', array_column($zipCodes, 'zip_code'));
                    });
                }
            } catch (Exception $e) {
                //return 'fail';
            }
        }

        $query->leftJoin('non_member_top_level_categories', $this->table . '.id', 'non_member_top_level_categories.non_member_id')
                ->select($this->table . '.*')
                ->with(['state', 'trade', 'service_category_type', 'top_level_category_list'])
                ->groupBy($this->table . '.id');
        
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
