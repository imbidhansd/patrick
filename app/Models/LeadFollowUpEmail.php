<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFollowUpEmail extends Model {

    protected $fillable = [
        'email_for',
        'lead_id',
        'follow_up_mail_category_id',
        'follow_up_email_id',
        'send_at', 'status',
    ];
    protected $table = 'lead_follow_up_emails';
    protected $dates = [
        'send_at',
    ];

    // foreign references 
    public function lead() {
        return $this->belongsTo('\App\Models\Lead', 'lead_id', 'id')->with(['company_lead', 'service_category', 'main_category']);
    }

    public function follow_up_email() {
        return $this->belongsTo('\App\Models\FollowUpEmail', 'follow_up_email_id', 'id');
    }

    public function follow_up_email_category() {
        return $this->belongsTo('\App\Models\FollowUpMailCategory', 'follow_up_mail_category_id', 'id');
    }

    // scopes
    public function scopePending($query) {
        return $query->where($this->table . '.status', 'pending');
    }

    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

}
