<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Email extends Model
{
	use LogsActivity;
    use \Bkwld\Cloner\Cloneable;
    
    protected $fillable = [
    	'email_for', 'title', 'subject',
        'email_header', 'email_content', 'email_footer',
        'email_type', 'send_time',
        'status'
    ];

    protected $table = 'emails';

    public $searchColumns = [
        'all' => 'All',
        'emails.title' => 'Title',
        'emails.email_for' => 'Email For',
    ];

    protected static $logAttributes = ['email_for','title', 'subject', 'email_header', 'email_content', 'email_footer', 'status'];


    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy($this->table.'.id', 'ASC');
    }

    public function scopeActive($query)
    {
        return $query->where($this->table.'.status', 'active');
    }

    public function getAdminList($params)
    {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }
}
