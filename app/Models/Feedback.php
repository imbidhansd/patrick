<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Feedback extends Model {

    use LogsActivity;

    protected $fillable = [
        'feedback_id', 'company_id',
        'feedback_status',
        'first_last_name',
        'customer_name', 'customer_email', 'customer_phone',
        'zipcode',
        'ratings', 'content',
        'activation_key',
    ];
    protected $table = 'feedback';
    public $searchColumns = [
        'all' => 'All',
        'feedback.feedback_id' => 'Feedback Number',
        'feedback.customer_name' => 'Customer Name',
        'feedback.customer_email' => 'Customer Email',
        'feedback.customer_phone' => 'Customer Phone',
    ];
    protected static $logAttributes = [
        'feedback_id', 'company_id',
        'feedback_status',
        'first_last_name',
        'customer_name', 'customer_email', 'customer_phone',
        'zipcode',
        'ratings', 'content',
        'activation_key',
    ];

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function feedback_files() {
        return $this->hasMany('App\Models\FeedbackFile', 'feedback_id', 'id')->with('media');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        $query->select([$this->table . '.*'])->with(['company', 'feedback_files']);
        return $query->paginate($record_per_page);
    }

    //Static functions
    public static function getFeedbackNumber() {
        $query = self::where('id', '>', 0);

        $from_date = \Carbon\Carbon::now()->format('Y-m-d');
        $query->whereRaw("DATE_FORMAT(feedback.created_at, '%Y-%m-%d')='" . $from_date . "'");
        $counter = $query->count() + 1;

        $order_number = 'R-' . \Carbon\Carbon::now()->format('Ymdhis') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
        return $order_number;
    }

}
