<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsqSession extends Model {

    protected $fillable = [
        'company_user_id', 'content',
    ];
    protected $table = 'psq_sessions';

}
