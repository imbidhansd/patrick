<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyZipcode extends Model {

    protected $fillable = [
        'company_id', 'zip_code', 'distance', 'city', 'state', 'state_id', 'status'
    ];
    protected $table = 'company_zipcodes';

    // Foreign References
    public function company() {
        $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function state() {
        $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

}
