<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonMemberFollowUpEmail extends Model {

    protected $fillable = [
        'non_member_id',
        'non_member_email_id',
        'send_at', 'status',
    ];
    protected $table = 'non_member_follow_up_emails';
    protected $dates = [
        'send_at',
    ];

    // foreign references 
    public function non_member() {
        return $this->belongsTo('\App\Models\NonMember', 'non_member_id', 'id');
    }

    public function non_member_email() {
        return $this->belongsTo('\App\Models\NonMemberEmail', 'non_member_email_id', 'id');
    }

    // scopes
    public function scopePending($query) {
        return $query->where($this->table . '.status', 'pending');
    }

    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

}
