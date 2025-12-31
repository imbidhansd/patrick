<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Trade extends Model {

    //
    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'old_id', 'media_id',
        'title', 'short_name', 'slug',
        'status', 'sort_order',
    ];
    protected $table = 'trades';
    public $searchColumns = [
        'all' => 'All',
        'trades.title' => 'Title',
    ];
    protected static $logAttributes = ['title', 'status', 'sort_order'];

    // Foreign Ref.
    public function follow_up_mail_category() {
        return $this->hasMany('App\Models\FollowUpMailCategory', 'trade_id', 'id')->with(['follow_up_emails', 'follow_up_confirmation_email'])->active()->order();
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('sort_order', 'ASC');
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
