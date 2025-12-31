<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Setting extends Model
{
    //
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'name', 'value', 'title', 'help_text',
        'field_type', 'field_options',
        'min_value', 'max_value',
        'min_length', 'max_length',
        'sort_order',
    ];
    protected $table = 'settings';

    public $searchColumns = [
        'all' => 'All',
        'settings.name' => 'Name',
        'settings.title' => 'Title',
    ];

    protected static $logAttributes = [
        'name', 'value', 'title', 'help_text',
        'field_type', 'field_options',
        'min_value', 'max_value',
        'min_length', 'max_length',
        'sort_order',
    ];

    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy('sort_order', 'ASC');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
