<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLeadPriority extends Model{
    protected $fillable = [
        'company_id', 'service_category_id',
        'zipcode', 'priority'
    ];

    protected $table = 'company_lead_priorities';

    // Foreign References
    public function company(){
    	return $this->belongsTo('App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function service_category(){
    	return $this->belongsTo('App\Models\ServiceCategory', 'service_category_id', 'id')->withDefault(['title' => '']);
    }

    // scopes
    public function scopeOrder($query){
    	return $query->where($this->table.'.priority', 'ASC');
    }
}
