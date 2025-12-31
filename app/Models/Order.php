<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'status',
        'total_price',
        'session_id',
        'company_id',
        'company_invoice1_id',
        'company_invoice2_id'
    ];
}
