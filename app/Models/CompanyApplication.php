<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyApplication extends Model{
    protected $fillable = [
        'company_id', 'application_key', 'application_value', 'application_value_type', 'expiry_date'
    ];
    protected $table = 'company_applications';

    // Foreign references
    public function company(){
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    // scopes
    public function scopeOrder($query){
    	return $this->orderBy($this->table.'.id', 'ASC');
    }
    
}
