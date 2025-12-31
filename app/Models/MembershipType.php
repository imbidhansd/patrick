<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;


class MembershipType extends Model
{
    //
    use Sluggable;
    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug',
        'content', 'renew_content', 'terms_content',
        'day_limit', 'number_of_days',
        'status', 'sort_order',
    ];
    protected $table = 'membership_types';

    public $searchColumns = [
        'all' => 'All',
        'membership_types.title' => 'Title',
    ];

    protected static $logAttributes = ['title', 'content', 'renew_content', 'terms_content', 'status', 'sort_order'];

    // Foreign Ref.

    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy('membership_types.id', 'DESC');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getAdminList($params)
    {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select(['membership_types.*']);
        return $query->paginate($record_per_page);
    }
}
