<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyListingAgreement extends Model {

    //
    //
    protected $fillable = [
        'company_id',
        'submitted_by',
        'employee_name',
        'employee_company_email',
        'true_information',
        'terms_of_use',
    ];
    protected $table = 'company_listing_agreements';

}
