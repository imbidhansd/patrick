<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLicensing extends Model {

    protected $fillable = [
        'company_id',
        'legally_registered_within_state',
        'state_business_registeration',
        'state_business_registeration_file_id',
        'proof_of_ownership',
        'copy_proof_of_ownership',
        'proof_of_ownership_file_id',
        'income_tax_filling',
        'articles_of_incorporation',
        'articles_of_incorporation_file_id',
        'licensing_required',
        //'no_licensing_required',
        //'state_licensing_required',
        'state_licensed',
        'copy_state_licensed',
        'state_licensed_file_id',
        //'country_licensing_required',
        'country_licensed',
        'copy_country_licensed',
        'country_licensed_file_id',
        //'city_licensing_required',
        'city_licensed',
        'copy_city_licensed',
        'city_licensed_file_id',
        'provide_written_warrenty',
        'written_warrenty',
        'written_warrenty_file_id',
        //
        'pre_screening_report_file_id',
        // Subcontractor agreement
        'subcontract_with_other_companies',
        'subcontractor_to_work_with_other_companies',
        'copy_of_subcontractor_agreement',
        'subcontractor_agreement_file_id'
    ];
    protected $table = 'company_licensings';

    // Foreign References
    public function proof_of_ownership_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'proof_of_ownership_file_id', 'id')->withDefault(['media' => '']);
    }

    public function state_business_registeration_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'state_business_registeration_file_id', 'id')->withDefault(['media' => '']);
    }

    public function articles_of_incorporation_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'articles_of_incorporation_file_id', 'id')->withDefault(['media' => '']);
    }

    public function state_licensed_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'state_licensed_file_id', 'id')->withDefault(['media' => '']);
    }

    public function country_licensed_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'country_licensed_file_id', 'id')->withDefault(['media' => '']);
    }

    public function city_licensed_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'city_licensed_file_id', 'id')->withDefault(['media' => '']);
    }

    public function written_warrenty_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'written_warrenty_file_id', 'id')->withDefault(['media' => '']);
    }

    public function pre_screening_report_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'pre_screening_report_file_id', 'id')->withDefault(['media' => '']);
    }

    public function subcontractor_agreement_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'subcontractor_agreement_file_id', 'id')->withDefault(['media' => '']);
    }

}
