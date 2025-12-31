<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipStatus extends Model {

    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'color',
        'receive_leads',
        'status'
    ];
    protected $table = 'membership_statuses';
    public $searchColumns = [
        'all' => 'All',
        'membership_statuses.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'color',
        'receive_leads',
        'status'
    ];

    // foreign references
    public function membership_level_status() {
        return $this->hasMany('\App\Models\MembershipLevelStatus', 'membership_status_id', 'id')->withDefault(['video_title' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select([$this->table . '.*']);
        return $query->paginate($record_per_page);
    }

}
