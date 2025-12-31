<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {

    protected $fillable = [
        'file_name', 'original_file_name',
        'file_type', 'file_extension',
    ];
    protected $table = 'media';
    public $searchColumns = [
        'all' => 'All',
        'media.file_name' => 'Filename',
    ];

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
