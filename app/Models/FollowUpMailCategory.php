<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class FollowUpMailCategory extends Model {

    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'trade_id', 'title', 'slug', 'status'
    ];
    protected $table = 'follow_up_mail_categories';
    public $searchColumns = [
        'all' => 'All',
        'follow_up_mail_categories.title' => 'Title',
    ];
    protected static $logAttributes = [
        'trade_id', 'title', 'slug', 'status'
    ];

    // Foreign Ref.
    public function trade() {
        return $this->belongsTo('App\Models\Trade', 'trade_id', 'id');
    }

    public function follow_up_confirmation_email() {
        return $this->hasOne('App\Models\FollowUpEmail', 'follow_up_mail_category_id', 'id')->confirmationEmail();
    }

    public function follow_up_emails() {
        return $this->hasMany('App\Models\FollowUpEmail', 'follow_up_mail_category_id', 'id')->followupEmail();
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'ASC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->with('trade')->select([$this->table . '.*']);
        return $query->paginate($record_per_page);
    }

}
