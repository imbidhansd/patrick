<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HideCompanyVideo extends Model {

    protected $fillable = [
        'company_id', 'membership_level_id', 'membership_status_id',
    ];
    protected $table = 'hide_company_videos';

}
