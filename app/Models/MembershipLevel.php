<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipLevel extends Model {

    //
    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'sub_title', 'charges_on_approval', 'whats_included',
        'content', 'renew_content', 'terms_content',
        'day_limit', 'number_of_days', 'lead_access', 'hide_leads',
        'paid_members', 'membership_fee', 'charge_type',
        'status', 'pay_by_check', 'video_id', 'sort_order',
        'pause_lead_message', 'short_content', 'color', 'is_popular',
    ];
    protected $table = 'membership_levels';
    public $searchColumns = [
        'all' => 'All',
        'membership_levels.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'sub_title', 'charges_on_approval', 'whats_included',
        'content', 'renew_content', 'terms_content',
        'day_limit', 'number_of_days', 'lead_access', 'hide_leads',
        'paid_members', 'membership_fee', 'charge_type',
        'status', 'pay_by_check', 'video_id', 'sort_order',
        'pause_lead_message', 'short_content', 'color', 'is_popular',
    ];

    // Foreign Ref.
    public function companies() {
        return $this->hasMany('App\Models\Company');
    }

    public function membership_level_status() {
        return $this->hasMany('\App\Models\MembershipLevelStatus', 'membership_level_id', 'id')->withDefault(['video_title' => '']);
    }

    public function membership_level_status_single() {
        return $this->hasOne('\App\Models\MembershipLevelStatus', 'membership_level_id', 'id')->withDefault(['video_title' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function scopePaidMember($query) {
        return $this->where($this->table . '.paid_members', 'yes');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select(['membership_levels.*']);
        return $query->paginate($record_per_page);
    }

}
