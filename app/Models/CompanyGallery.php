<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyGallery extends Model {

    use LogsActivity;

    protected $fillable = [
        'company_id', 'media_id',
        'gallery_type', 'video_type', 'video_id', 'image_link',
        'status', 'reject_note', 'sort_order'
    ];
    protected $table = 'company_galleries';
    public $searchColumns = [
        'all' => 'All',
    ];
    protected static $logAttributes = [
        'company_id', 'media_id',
        'gallery_type', 'video_type', 'video_id', 'image_link',
        'status', 'reject_note', 'sort_order'
    ];

    // Foreign References
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeStatus($query, $status_type) {
        return $query->where($this->table . '.status', $status_type);
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->with(['company', 'media']);

        return $query->paginate($record_per_page);
    }

}
