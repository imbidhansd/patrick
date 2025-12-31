<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipLevelStatus extends Model {

    protected $fillable = [
        'membership_level_id', 'membership_status_id',
        'video_id', 'video_title',
    ];
    protected $table = 'membership_level_statuses';
    public $searchColumns = [
        'all' => 'All',
        'membership_level_statuses.video_title' => 'Video Title',
    ];

    // foreign references
    public function membership_level() {
        return $this->belongsTo('\App\Models\MembershipLevel', 'membership_level_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function membership_status() {
        return $this->belongsTo('\App\Models\MembershipStatus', 'membership_status_id', 'id')->active()->withDefault(['title' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('membership_level_statuses.id', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where('id', '>', 0);
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $query->with(['membership_level', 'membership_status']);
        $query->leftJoin('membership_levels', 'membership_levels.id', '=', 'membership_level_statuses.membership_level_id');
        $query->leftJoin('membership_statuses', 'membership_statuses.id', '=', 'membership_level_statuses.membership_status_id');
        $query->select([$this->table . '.*'])->whereNotNull($this->table.'.video_id');

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
