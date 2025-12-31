<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLeadNotification extends Model {

    //

    protected $fillable = [
        'company_id',
        'main_email_address',
        'receive_a_copy',
        'owner_2', 'owner_2_name', 'owner_2_email',
        'owner_3', 'owner_3_name', 'owner_3_email',
        'owner_4', 'owner_4_name', 'owner_4_email',
        'office_manager', 'office_manager_name', 'office_manager_email',
        'sales_manager', 'sales_manager_name', 'sales_manager_email',
        'estimators_sales_1', 'estimators_sales_1_name', 'estimators_sales_1_email',
        'estimators_sales_2', 'estimators_sales_2_name', 'estimators_sales_2_email',
    ];
    protected $table = 'company_lead_notifications';

}
