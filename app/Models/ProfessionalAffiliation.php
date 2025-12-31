<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class ProfessionalAffiliation extends Model {

    use Sluggable;
    use LogsActivity;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'trade_id',
        'title', 'slug',
        'sort_order', 'status'
    ];
    protected $table = 'professional_affiliations';
    public $searchColumns = [
        'all' => 'All',
        'professional_affiliations.title' => 'Title',
    ];
    protected static $logAttributes = [
        'trade_id',
        'title', 'slug',
        'sort_order', 'status'
    ];

    // Foreign Ref.
    public function trade() {
        return $this->belongsTo('\App\Models\Trade', 'trade_id', 'id')->active()->withDefault(['title' => '']);
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

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

}
