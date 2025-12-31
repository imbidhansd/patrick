<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class VerifyMember extends Model {

    protected $fillable = [
        'company_id', 'phone_number'
    ];
    protected $table = 'verify_members';
    public $searchColumns = [
        'all' => 'All',
        'verify_members.phone_number' => 'Phone Number',
    ];

    // Foreign references
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        
        return $query->select('*')
                        //->groupBy('phone_number')
                        ->paginate($record_per_page);
    }

}
