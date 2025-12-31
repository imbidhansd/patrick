<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfileView extends Model {

    protected $fillable = [
        'company_id', 'session', 'ip_address',
    ];
    protected $table = 'company_profile_views';

}
