<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageProduct extends Model{
    protected $fillable = [
        'package_id', 'product_id', 'product_price'
    ];

    protected $table = 'package_products';

    // Foreign References
    public function package (){
    	return $this->belongsTo('\App\Models\Package', 'package_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function product (){
    	return $this->belongsTo('\App\Models\Product', 'product_id', 'id')->active()->withDefault(['title' => '']);
    }
}
