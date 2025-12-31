<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCustomerReference extends Model {

    //
    protected $fillable = [
        'company_id',
        'ref_type',
        'customers',
        'professional_affiliations',
        'other_professional_affiliations',
        'customer_references_file_id'
    ];
    protected $table = 'company_customer_references';

    public function customer_reference_file() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'customer_references_file_id', 'id')->with('media');
    }

}
