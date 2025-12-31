<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model {

    protected $fillable = [
        'company_id',
        //
        'company_owner_1_full_name',
        'company_owner_1_email',
        'company_owner_1_phone',
        'company_owner_1_user_id',
        'company_owner_1_invitation_key',
        'company_owner_1_status',
        //
        'company_owner_2_full_name',
        'company_owner_2_email',
        'company_owner_2_phone',
        'company_owner_2_user_id',
        'company_owner_2_invitation_key',
        'company_owner_2_status',
        //
        'company_owner_3_full_name',
        'company_owner_3_email',
        'company_owner_3_phone',
        'company_owner_3_user_id',
        'company_owner_3_invitation_key',
        'company_owner_3_status',
        //
        'company_owner_4_full_name',
        'company_owner_4_email',
        'company_owner_4_phone',
        'company_owner_4_user_id',
        'company_owner_4_invitation_key',
        'company_owner_4_status',
        //
        'legal_company_name', 'ein', 'company_start_date',
        'main_company_telephone', 'website',
        'mailing_address', 'suite', 'city',
        'state_id', 'county', 'zipcode',
        'internal_contact_fullname',
        'internal_contact_phone',
        'internal_contact_email',
    ];
    protected $table = 'company_information';

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function company_owner1() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_owner_1_user_id', 'id')->active();
    }

    public function company_owner2() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_owner_2_user_id', 'id')->active();
    }

    public function company_owner3() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_owner_3_user_id', 'id')->active();
    }

    public function company_owner4() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_owner_4_user_id', 'id')->active();
    }

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id');
    }

}
