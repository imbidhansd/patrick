<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Testimonial extends Model {

    //
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'company_name', 'owner_name', 'media_id',
        'website', 'telephone', 'city', 'state_id', 'zipcode',
        'content',
        'status', 'sort_order',
    ];
    protected $table = 'testimonials';
    public $searchColumns = [
        'all' => 'All',
        'testimonials.company_name' => 'Company Name',
        'testimonials.owner_name' => 'Owner Name',
        'testimonials.website' => 'Website',
    ];
    protected static $logAttributes = [
        'company_name', 'owner_name', 'media_id',
        'website', 'telephone', 'city', 'state_id', 'zipcode',
        'content',
        'status', 'sort_order',
    ];

    // Foreign Ref.
    public function state() {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
