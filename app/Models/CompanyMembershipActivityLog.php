<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMembershipActivityLog extends Model {

    protected $fillable = [
        'company_id', 'couser_id',
        'from_membership_level_id', 'from_membership_status_id',
        'membership_level_id', 'membership_status_id',
        'ip_address',
    ];
    protected $table = 'company_membership_activity_logs';

    // Foreign References
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function admin_user() {
        return $this->belongsTo('\App\Models\User', 'couser_id', 'id')->withDefault(['first_name' => '', 'last_name' => '']);
    }

    public function from_membership_level() {
        return $this->belongsTo('\App\Models\MembershipLevel', 'from_membership_level_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function from_membership_status() {
        return $this->belongsTo('\App\Models\MembershipStatus', 'from_membership_status_id', 'id')->active()->withDefault(['title' => '']);
    }
    
    public function membership_level() {
        return $this->belongsTo('\App\Models\MembershipLevel', 'membership_level_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function membership_status() {
        return $this->belongsTo('\App\Models\MembershipStatus', 'membership_status_id', 'id')->active()->withDefault(['title' => '']);
    }

    // scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

}
