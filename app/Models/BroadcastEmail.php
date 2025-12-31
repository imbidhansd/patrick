<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BroadcastEmail extends Model {

    use LogsActivity;

    protected $fillable = [
        'email_type',
        'email_for',
        'trade_id', 'top_level_category_id', 'main_category_id', 'service_category_id',
        'zipcode', 'mile_range',
        'subject',
        'email_header_id', 'email_header',
        'content',
        'email_footer_id', 'email_footer',
        'send_datetime',
        'from_email_address',
        'subscription_type',
        'mail_sent',
        'draft_message'
    ];
    protected $table = 'broadcast_emails';
    public $searchColumns = [
        'all' => 'All',
        'broadcast_emails.subject' => 'Subject'
    ];
    protected static $logAttributes = [
        'email_type',
        'trade_id', 'top_level_category_id', 'main_category_id', 'service_category_id',
        'zipcode', 'mile_range',
        'subject',
        'email_header_id', 'email_header',
        'content',
        'email_footer_id', 'email_footer',
        'send_datetime',
        'from_email_address',
        'subscription_type',
        'mail_sent',
        'draft_message'
    ];

    // Foreign References
    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function top_level_category() {
        return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function service_category() {
        return $this->belongsTo('\App\Models\ServiceCategory', 'service_category_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function email_header_content() {
        return $this->belongsTo('\App\Models\DefaultEmailHeaderFooter', 'email_header_id', 'id')->emailtype('header');
    }

    public function email_footer_content() {
        return $this->belongsTo('\App\Models\DefaultEmailHeaderFooter', 'email_footer_id', 'id')->emailtype('footer');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        if (isset($params['email_type']) && $params['email_type'] != '') {
            $query->where('email_type', $params['email_type']);
        } else {
            $query->whereNull('email_type');
        }


        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
