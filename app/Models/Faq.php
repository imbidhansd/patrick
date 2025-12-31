<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Faq extends Model
{

    use LogsActivity;
    use \Bkwld\Cloner\Cloneable;

    protected $fillable = [
        'membership_level_id', 'membership_status_id',
        'title', 'content', 'video_id',
        'status', 'sort_order'
    ];
    protected $table = 'faqs';

    public $searchColumns = [
        'all' => 'All',
        'faqs.title' => 'Question',
        'faqs.content' => 'Answer',
        'faqs.video_id' => 'Vimeo Video ID',
    ];

    // Foreign Ref.
    public function membership_level (){
        return $this->belongsTo('\App\Models\MembershipLevel', 'membership_level_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function membership_status (){
        return $this->belongsTo('\App\Models\MembershipStatus', 'membership_status_id', 'id')->active()->withDefault(['title' => '']);
    }


    // Scopes
    public function scopeOrder($query)
    {
        return $query->orderBy($this->table . '.sort_order', 'ASC');
    }

    public function scopeActive($query)
    {
        return $query->where($this->table . '.status', 'active');
    }

    public function getAdminList($params)
    {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->with(['membership_level', 'membership_status']);
        return $query->paginate($record_per_page);
    }
}
