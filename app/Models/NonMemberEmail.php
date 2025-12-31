<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NonMemberEmail extends Model {

    use LogsActivity;

    protected $fillable = [
        'title', 'subject',
        'email_header_id', 'email_header',
        'email_content',
        'email_footer_id', 'email_footer',
        'email_type', 'send_time',
        'from_email_address',
        'sort_order', 'status'
    ];
    protected $table = 'non_member_emails';
    public $searchColumns = [
        'all' => 'All',
        'non_member_emails.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'subject',
        'email_header_id', 'email_header',
        'email_content',
        'email_footer_id', 'email_footer',
        'email_type', 'send_time',
        'from_email_address',
        'sort_order', 'status'
    ];
    
    // Foreign Ref.
    public function email_header_content() {
        return $this->belongsTo('\App\Models\DefaultEmailHeaderFooter', 'email_header_id', 'id')->emailtype('header');
    }

    public function email_footer_content() {
        return $this->belongsTo('\App\Models\DefaultEmailHeaderFooter', 'email_footer_id', 'id')->emailtype('footer');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function scopeConfirmationEmail($query) {
        return $query->where($this->table . '.email_type', 'confirmation_email');
    }

    public function scopeFollowupEmail($query) {
        return $query->where($this->table . '.email_type', 'followup_email');
    }

}
