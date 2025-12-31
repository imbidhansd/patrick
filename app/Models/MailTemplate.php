<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MailTemplate extends Model
{
    //

    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'title', 'email_for', 'mailable',
        'subject', 'html_template', 'text_template',
    ];
    protected $table = 'mail_templates';

    public $searchColumns = [
        'all' => 'All',
        'mail_templates.title' => 'Title',
        'mail_templates.subject' => 'Subject',
        'mail_templates.html_template' => 'HTML Template',
    ];

    protected static $logAttributes = ['subject', 'html_template'];

    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query)
    {
        return $query->where($this->table . '.id', '!=', '0');
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
