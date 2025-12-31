<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyOwnerPreScreenQuestion extends Model {

    protected $fillable = [
        'company_id', 'company_user_id',
        'first_name', 'middle_name', 'last_name', 'gender',
        'email', 'telephone', 'birth_date', 'driver_license_id',
        //
        'convicted_in_fraud', 'convicted_in_felony', 'bankruptcy',
        'other_business_name', 'business_name_list', 'changed_name', 'changed_name_list', 
        //
        'address', 
        //
        'signature',
        //
        'address_line_1', 'address_line_2', 'city', 'state', 'zipcode',
        //
        'ip_address','ssn', 'bg_check_pdf_id', 'pre_screen_question_file_id',
    ];
    protected $table = 'company_owner_pre_screen_questions';

    // Foreign Reference
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function driver_license() {
        return $this->belongsTo('\App\Models\Media', 'driver_license_id', 'id');
    }


    public function bg_check_pdf() {
        return $this->belongsTo('\App\Models\Media', 'bg_check_pdf_id', 'id');
    }

    public function pre_screen_question_file() {
        return $this->belongsTo('\App\Models\Media', 'pre_screen_question_file_id', 'id');
    }

    public function company_user() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_user_id', 'id')->withDefault([
                    'first_name' => '',
                    'last_name' => ''
        ]);
    }

}
