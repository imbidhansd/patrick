<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class CouponType extends Model {

    //
    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'status'
    ];
    protected $table = 'coupon_types';
    public $searchColumns = [
        'all' => 'All',
        'coupon_types.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'status'
    ];

    // Foreign Ref.
    public function coupons() {
        return $this->hasMany('App\Models\Coupon', 'coupon_type_id', 'id')->active()->order();
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
