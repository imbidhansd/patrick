<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model {

    protected $fillable = [
        'complaint_id', 'couser_id', 'media_id', 'couser_type', 'content'
    ];
    protected $table = 'complaint_responses';

    // Foreign References 
    public function complaint() {
        return $this->belongsTo('App\Models\Complaint', 'complaint_id', 'id')->with('company');
    }

    public function media() {
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    // Scopes 
    public function scopeOrder($query) {
        return $query->orderBy($this->table . '.id', 'DESC');
    }

}
