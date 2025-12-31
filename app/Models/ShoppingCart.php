<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    //
    protected $fillable = [
        'session_id', 'company_id', 'content',
    ];
    protected $table = 'shopping_carts';
}
