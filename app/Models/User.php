<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable {

    use Notifiable;
    use HasRoles;
    use LogsActivity;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'google2fa_secret',
        'first_name', 'last_name', 'email',
        'username', 'password', 'media_id',
        // Company Fields
        'company_name', 'company_website_url',
        'main_company_telephone', 'secondary_telephone', 'company_mailing_address',
        'suite', 'city', 'state_id', 'zipcode', 'status',
        // Admin User Fields
        'designation', 'about_user',
        'facebook_page', 'twitter_page', 'linkedin_page',
        'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $table = 'users';
    public $searchColumns = [
        'all' => 'All',
        'users.first_name' => 'First Name',
        'users.email' => 'Email',
        'users.username' => 'Username',
        'users.city' => 'City',
        'users.zipcode' => 'Zipcode',
    ];
    protected static $logAttributes = [
        'role_id',
        'first_name', 'last_name', 'email',
        'username', 'password', 'media_id',
        // Company Fields
        'company_name', 'company_website_url',
        'main_company_telephone', 'secondary_telephone', 'company_mailing_address',
        'suite', 'city', 'state_id', 'zipcode', 'status',
        // Admin User Fields
        'designation', 'about_user',
        'facebook_page', 'twitter_page', 'linkedin_page',
    ];

    // Foreign Ref.
    public function role() {
        return $this->belongsTo('Spatie\Permission\Models\Role', 'role_id', 'id')->withDefault(['name' => null]);
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    // Scopes
    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    // Attributes
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAdminList($params) {
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }

    // Permission Methods
    /* public function hasAnyPermission($permissions = [])
      {
      foreach ($permissions as $permission) {
      if ($this->checkPermissionTo($permission)) {
      return true;
      }
      }
      return false;
      } */
}
