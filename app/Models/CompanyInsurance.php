<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInsurance extends Model{
    
    protected $fillable = [
        'company_id',
        'general_liability_insurance_and_worker_compensation_insurance',
        'same_insurance_agent_agency',
        'general_liability_insurance_agent_agency_name',
        'general_liability_insurance_agent_agency_phone_number',
        'gen_lia_ins_file_id',
        'general_liability_insurance_expiry_date',
        'general_liability_insurance_mark_as_completed_date',
        'workers_compensation_insurance_agent_agency_name',
        'workers_compensation_insurance_agent_agency_phone_number',
        'work_com_ins_file_id',
        'workers_compensation_insurance_expiry_date',
        'workers_compensation_insurance_mark_as_completed_date',
    ];

    protected $table = 'company_insurances';


    public function liability_insurance_file (){
        return $this->belongsTo('\App\Models\CompanyDocument', 'gen_lia_ins_file_id', 'id')->with('media');
    }

    public function compensation_insurance_file (){
        return $this->belongsTo('\App\Models\CompanyDocument', 'work_com_ins_file_id', 'id')->with('media');
    }
}
