<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLead extends Model {

    protected $fillable = [
        'company_id', 'lead_id', 'fee',
        'is_hidden', 'is_checked', 'priority'
    ];
    protected $table = 'company_leads';

    // Foreign References
    public function company_detail() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->with('company_lead_notification')->withDefault(['company_name' => '']);
    }
    
    public function company_name_admin_list (){
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function lead() {
        //return $this->belongsTo('\App\Models\Lead', 'lead_id', 'id')->with(['service_category', 'main_category', 'service_category_type', 'state'])->activated();
        return $this->belongsTo('\App\Models\Lead', 'lead_id', 'id')->with(['service_category', 'main_category', 'service_category_type', 'state']);
    }

    //scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.priority', 'ASC');
    }

    public function scopeIsNotChecked($query) {
        return $query->where($this->table . '.is_checked', 'no');
    }

}
