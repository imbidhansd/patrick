<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackFile extends Model{
    protected $fillable = [
        'company_id', 'feedback_id', 'media_id'
    ];
    protected $table = 'feedback_files';

    // Foreign Ref.
    public function company() {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function feedback() {
        return $this->belongsTo('App\Models\Feedback', 'feedback_id', 'id');
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }
}