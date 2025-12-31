<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Complaint extends Model {

    use LogsActivity;

    protected $fillable = [
        'complaint_id', 'company_id',
        'complaint_status',
        'first_last_name',
        'customer_name', 'customer_email', 'customer_phone',
        'zipcode',
        'have_contract_agreement', 'contract_agreement_file_id',
        'content',
        'confirmed_mail_sent',
    ];
    protected $table = 'complaints';
    public $searchColumns = [
        'all' => 'All',
        'complaints.complaint_id' => 'Complaint Number'
    ];
    protected static $logAttributes = [
        'complaint_id', 'company_id',
        'complaint_status',
        'first_last_name',
        'customer_name', 'customer_email', 'customer_phone',
        'zipcode',
        'have_contract_agreement', 'contract_agreement_file_id',
        'content',
        'confirmed_mail_sent',
    ];

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function complaint_files() {
        return $this->hasMany('\App\Models\ComplaintFile', 'complaint_id', 'id')->with('media');
    }

    public function complaint_response() {
        return $this->hasMany('\App\Models\ComplaintResponse', 'complaint_id', 'id')->with('media')->order();
    }

    public function contract_agreement_file() {
        return $this->belongsTo('\App\Models\Media', 'contract_agreement_file_id', 'id');
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
        $query->select([$this->table . '.*'])->with(['company', 'complaint_files', 'complaint_response']);
        return $query->paginate($record_per_page);
    }

    //Static functions
    public static function getComplaintNumber() {
        $query = self::where('id', '>', 0);

        $from_date = \Carbon\Carbon::now()->format('Y-m-d');
        $query->whereRaw("DATE_FORMAT(complaints.created_at, '%Y-%m-%d')='" . $from_date . "'");
        $counter = $query->count() + 1;

        $order_number = 'C-' . \Carbon\Carbon::now()->format('Ymdhis') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
        return $order_number;
    }

}
