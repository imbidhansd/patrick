<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DefaultEmailHeaderFooter extends Model {

    use LogsActivity;

    protected $fillable = [
        'title', 'content_type', 'content'
    ];
    protected $table = 'default_email_header_footers';
    public $searchColumns = [
        'all' => 'All',
        'default_email_header_footers.title' => 'Title',
    ];
    protected static $logAttributes = ['title', 'content_type', 'content'];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

    public function scopeEmailtype($query, $email_type) {
        return $query->where($this->table . '.content_type', $email_type);
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
