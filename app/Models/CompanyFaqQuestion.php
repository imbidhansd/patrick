<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyFaqQuestion extends Model {

    use LogsActivity;

    protected $fillable = [
        'company_id', 'company_user_id',
        'question', 'content',
        'status'
    ];
    protected $table = 'company_faq_questions';
    public $searchColumns = [
        'all' => 'All',
        'company_faq_questions.question' => 'Question',
        'company_faq_questions.content' => 'Question Content',
        'companies.company_name' => 'Company',
        'company_users.first_name' => 'Company User',
    ];

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['company_name' => '']);
    }

    public function company_user() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_user_id', 'id')->withDefault(['first_name' => '', 'last_name' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $query->leftJoin('companies', 'companies.id', '=', 'company_faq_questions.company_id');
        $query->leftJoin('company_users', 'company_users.id', '=', 'company_faq_questions.company_user_id');
        $query->select('company_faq_questions.*');
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        //$query->with(['membership_level', 'membership_status']);
        return $query->paginate($record_per_page);
    }

}
