<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyNote extends Model {

    protected $fillable = [
        'company_id', 'created_by', 'notes'
    ];
    protected $table = 'company_notes';

    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function user() {
        return $this->belongsTo('\App\Models\User', 'created_by', 'id');
    }

}
