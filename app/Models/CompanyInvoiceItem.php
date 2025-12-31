<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoiceItem extends Model {

    protected $fillable = [
        'company_invoice_id', 'title', 'description',
        'amount', 'qty', 'total'
    ];
    protected $table = 'company_invoice_items';

    public function company_invoice() {
        return $this->belongsTo('\App\Models\CompanyInvoice', 'company_invoice_id', 'id');
    }

}
