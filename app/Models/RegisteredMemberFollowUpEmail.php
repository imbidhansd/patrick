<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisteredMemberFollowUpEmail extends Model {

    protected $fillable = [
        'company_id',
        'reg_mem_email_id',
        'send_at', 'status',
    ];
    protected $table = 'registered_member_follow_up_emails';
    protected $dates = [
        'send_at',
    ];

    // foreign references 
    public function reg_member() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function reg_mem_email() {
        return $this->belongsTo('\App\Models\RegisteredMemberEmail', 'reg_mem_email_id', 'id');
    }

    // scopes
    public function scopePending($query) {
        return $query->where($this->table . '.status', 'pending');
    }

    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

}
