<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Artwork extends Model {

    use LogsActivity;

    protected $fillable = [
        'artwork_type', 'artwork_for', 'social_type',
        'title',
        'jpg_url', 'png_url', 'pdf_url',
        'jpg_media_id', 'png_media_id', 'pdf_media_id',
        'image_type',
        'status', 'sort_order',
    ];
    protected $table = 'artworks';
    public $searchColumns = [
        'all' => 'All',
        'artworks.title' => 'Title',
        'artworks.image_type' => 'Type',
    ];
    protected static $logAttributes = [
        'artwork_type', 'artwork_for', 'social_type',
        'title',
        'jpg_url', 'png_url', 'pdf_url',
        'jpg_media_id', 'png_media_id', 'pdf_media_id',
        'image_type',
        'status', 'sort_order',
    ];

    // Foreign References
    public function jpg_media() {
        return $this->belongsTo('\App\Models\Media', 'jpg_media_id', 'id');
    }

    public function png_media() {
        return $this->belongsTo('\App\Models\Media', 'png_media_id', 'id');
    }

    public function pdf_media() {
        return $this->belongsTo('\App\Models\Media', 'pdf_media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        if (isset($params['artwork_type']) && $params['artwork_type'] != '') {
            $query->where($this->table . '.artwork_type', $params['artwork_type']);
        }

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
