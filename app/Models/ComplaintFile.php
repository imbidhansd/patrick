<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintFile extends Model{

    protected $fillable = [
        'company_id', 'complaint_id', 'media_id'
    ];
    protected $table = 'complaint_files';

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function complaint() {
        return $this->belongsTo('App\Models\Complaint', 'complaint_id', 'id');
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }
}
