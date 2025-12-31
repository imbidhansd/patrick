<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
         'message',
         'context', 'key_identifier',
         'key_identifier_type'
     ];
     protected $table = 'custom_logs';

     
}
