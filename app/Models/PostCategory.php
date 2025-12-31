<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class PostCategory extends Model
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
        'meta_title', 'meta_keywords', 'meta_description',
        'status', 'sort_order',
    ];
    protected $table = 'post_categories';

    public $searchColumns = [
        'all' => 'All',
        'post_categories.title' => 'Title',
    ];

    protected static $logAttributes = ['title', 'meta_title', 'meta_keywords', 'meta_description', 'status', 'sort_order'];

    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy('sort_order', 'ASC');
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
        return $query->paginate($record_per_page);
    }
}
