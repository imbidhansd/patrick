<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use Spatie\Activitylog\Traits\LogsActivity;

class CompanyMessage extends Model {

    //
    //use LogsActivity;

    protected $fillable = [
        'company_id', 'message_type',
        'title', 'content', 'link',
        'checked', 'checked_at', 'deleted'
    ];
    protected $table = 'company_messages';
    public $searchColumns = [
        'all' => 'All',
        'company_messages.title' => 'Title',
        'company_messages.content' => 'Content',
        'company_messages.link' => 'Link',
    ];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('checked', 'ASC')->orderBy('id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('deleted', 'no');
    }

    public function scopeChecked($query) {
        return $query->where('checked', 'yes');
    }

    public function scopeNotChecked($query) {
        return $query->where('checked', 'no');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
