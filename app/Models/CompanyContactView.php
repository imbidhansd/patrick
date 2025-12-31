<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContactView extends Model {

    protected $fillable = [
        'company_id', 'session_id',
    ];
    protected $table = 'company_contact_views';

}
