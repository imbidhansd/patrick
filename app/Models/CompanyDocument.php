<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model {

    protected $fillable = [
        'company_id',
        'document_type',
        'file_id',
        'expiration_date',
        'status',
        'reject_note',
        'upload_by', 'company_owner_id', 'admin_id'
    ];
    protected $table = 'company_documents';

    // Foreign References
    public function media() {
        return $this->belongsTo('\App\Models\Media', 'file_id', 'id')->withDefault(['file_name' => '']);
    }

    public function company_owner() {
        return $this->belongsTo('\App\Models\CompanyUser', 'company_owner_id', 'id');
    }

    public function admin_user() {
        return $this->belongsTo('\App\Models\User', 'admin_id', 'id');
    }

}
