<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoice extends Model {

    protected $fillable = [
        'company_id', 'ship_address_id' , 'bill_address_id',
        'invoice_type', 'payment_type',
        'invoice_date', 'invoice_id',
        'invoice_for', 'final_amount',
        'transaction_id', 'subscription_id', 'subscription_pay_number',
        'invoice_paid_date',
        'status', 'note'
    ];
    protected $table = 'company_invoices';
    protected $dates = ['invoice_date', 'invoice_paid_date'];

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->with('state');
    }

    public function ship_address() {
        return $this->belongsTo('\App\Models\CompanyInvoiceAddress', 'ship_address_id', 'id')->with('state');
    }
    public function bill_address() {
        return $this->belongsTo('\App\Models\CompanyInvoiceAddress', 'bill_address_id', 'id')->with('state');
    }

    public function company_invoice_item() {
        return $this->hasMany('\App\Models\CompanyInvoiceItem', 'company_invoice_id', 'id');
    }

    // Date Fields
    public function setInvoiceDateAttribute($input) {
        if ($input != '') {
            $this->attributes['invoice_date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $input)->format(env('DB_DATE_FORMAT'));
        }
    }

    public function getInvoiceDateAttribute($input) {
        if ($input != '') {
            return \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT'), $input)->format(env('DATE_FORMAT'));
        }
    }

    public function setInvoicePaidDateAttribute($input) {
        if ($input != '') {
            $this->attributes['invoice_paid_date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $input)->format(env('DB_DATE_FORMAT'));
        }
    }

    public function getInvoicePaidDateAttribute($input) {
        if ($input != '') {
            return \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT'), $input)->format(env('DATE_FORMAT'));
        }
    }

    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC');
    }

    //Static functions
    public static function getOrderNumber() {

        $query = self::where('id', '>', 0);

        $from_date = \Carbon\Carbon::now()->format('Y-m-d');
        $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')='$from_date'");
        $counter = $query->count() + 1;

        $order_number = \Carbon\Carbon::now()->format('Ymdhis') . str_pad($counter, 3, '0', STR_PAD_LEFT);
        return $order_number;
    }

}
