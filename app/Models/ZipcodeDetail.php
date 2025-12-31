<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipcodeDetail extends Model
{
    protected $table = 'zipcode_details';
    
    protected $fillable = [
        'parent_zip_code',
        'zip_code',
        'distance',
        'city',
        'state',
        'state_id',
        'status',
    ];
}
