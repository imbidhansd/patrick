<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FollowUpEmail extends Model {

    use LogsActivity;

    protected $fillable = [
        'email_for', 'title', 'subject',
        'email_header_id', 'email_header',
        'email_content',
        'email_footer_id', 'email_footer',
        'trade_id', 'follow_up_mail_category_id', 'email_type', 'send_time',
        'from_email_address',
        'subscription_type',
        'sort_order', 'status'
    ];
    protected $table = 'follow_up_emails';
    public $searchColumns = [
        'all' => 'All',
        'follow_up_emails.title' => 'Title',
        'follow_up_emails.email_for' => 'Email For',
    ];
    protected static $logAttributes = [
        'email_for', 'title', 'subject',
        'email_header_id', 'email_header',
        'email_content',
        'email_footer_id', 'email_footer',
        'trade_id', 'follow_up_mail_category_id', 'email_type', 'send_time',
        'from_email_address',
        'subscription_type',
        'sort_order', 'status'
    ];

    // Foreign Ref.
    public function trade() {
        return $this->belongsTo('App\Models\Trade', 'trade_id', 'id');
    }

    public function follow_up_email_category() {
        return $this->belongsTo('App\Models\FollowUpMailCategory', 'follow_up_mail_category_id', 'id')->active()->withDefault(['title' => '']);
    }

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
