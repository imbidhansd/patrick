<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CompanyUser extends Authenticatable {

    protected $guard = 'company_user';
    protected $fillable = [
        'company_id', 'company_user_type',
        'first_name', 'last_name',
        'email', 'user_telephone',
        'user_bio', 'media_id',
        'user_bio_status', 'user_bio_reject_note', 'user_image_status', 'user_image_reject_note',
        'bg_check_document_id', 'bg_check_status', 'bg_check_date', 'bg_check_content', 'bg_check_order_id',
        'address', 'city', 'state_id', 'zipcode',
        'username', 'password',
        'status',
        'forgot_password_key'
    ];
    protected $table = 'company_users';
    public $searchColumns = [
        'all' => 'All',
        'company_users.first_name' => 'Name',
        'company_users.email' => 'Email',
        'company_users.username' => 'Username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthPassword() {
        return $this->password;
    }

    // Foreign References
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id')->withDefault(['title' => '']);
    }

    public function state() {
        return $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    public function bg_check_report() {
        return $this->belongsTo('\App\Models\CompanyDocument', 'bg_check_document_id', 'id')->withDefault(['media' => '']);
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where($this->table . '.status', 'active');
    }

    public function scopeRemainingApproval($query) {
        return $query->where($this->table . '.user_bio_status', 'in process')->orWhere($this->table . '.user_image_status', 'in process');
    }

    public function full_name() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);
        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);

        $query->select($this->table . '.*')->leftJoin('companies', $this->table . '.company_id', 'companies.id')->with(['company', 'state']);

        if (isset($params['company_id']) && $params['company_id'] != '') {
            $query->where('company_id', $params['company_id']);
        }
        return $query->paginate($record_per_page);
    }

}
