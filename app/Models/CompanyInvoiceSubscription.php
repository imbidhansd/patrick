<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoiceSubscription extends Model {

    protected $fillable = [
        'company_id', 'invoice_id',
        'transaction_id'
    ];
    protected $table = 'company_invoice_subscriptions';

    // Foreign References
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->active()->withDefault(['company_name' => '']);
    }

    public function invoice() {
        return $this->belongsTo('\App\Models\CompanyInvoice', 'invoice_id', 'id');
    }

}
