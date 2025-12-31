<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterationSession extends Model {

    //
    protected $fillable = [
        'registration_type',
        'session_id', 'content',
    ];
    protected $table = 'registeration_sessions';

}
