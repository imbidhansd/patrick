<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoiceAddress extends Model {

    //
    protected $fillable = [
        'company_id', 'address_type',
        'company_name', 'first_name', 'last_name',
        'mailing_address', 'suite', 'city', 'state_id',
        'county', 'zipcode', 'phone',
    ];
    protected $table = 'company_invoice_addresses';

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => null]);
    }

}
