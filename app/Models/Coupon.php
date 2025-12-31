<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Coupon extends Model {

    //
    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug', 'coupon_code',
        'coupon_type_id', 'coupon_amount', 'description',
        'product_id', 'usage_limit', 'expiration_date',
        'status'
    ];
    protected $table = 'coupons';
    public $searchColumns = [
        'all' => 'All',
        'coupons.title' => 'Title',
    ];
    protected static $logAttributes = [
        'title', 'slug', 'coupon_code',
        'coupon_type_id', 'coupon_amount', 'description',
        'product_id', 'usage_limit', 'expiration_date',
        'status'
    ];

    // Foreign Ref.
    public function coupon_type() {
        return $this->belongsTo('App\Models\CouponType', 'coupon_type_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->active()->withDefault(['title' => '']);
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
