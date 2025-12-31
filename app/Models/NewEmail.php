<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NewEmail extends Model {

    use LogsActivity;

    protected $fillable = [
        'email_for', 'email_type',
        'title', 'subject',
        'email_header_id', 'email_header',
        'content',
        'email_footer_id', 'email_footer',
        'from_email_address', 'status'
    ];
    protected $table = 'new_emails';
    public $searchColumns = [
        'all' => 'All',
        'new_emails.subject' => 'Subject',
    ];
    protected static $logAttributes = [
        'email_for', 'email_type',
        'title', 'subject',
        'email_header_id', 'email_header',
        'content',
        'email_footer_id', 'email_footer',
        'from_email_address', 'status'
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
        return $query->orderBy($this->table . '.id', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->Table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);


        if (isset($params['email_for']) && $params['email_for'] != '') {
            $query->where($this->table . '.email_for', $params['email_for']);
        }

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->active()->paginate($record_per_page);
    }

}
