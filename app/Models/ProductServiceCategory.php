<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductServiceCategory extends Model{
    protected $fillable = [
        'product_id',
        'top_level_category_id', 'main_category_id', 'service_category_id', 'service_category_type_id'
    ];

    protected $table = 'product_service_categories';

    // Foreign references
    public function product (){
    	return $this->belongsTo('\App\Models\Product', 'product_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function top_level_category (){
    	return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function main_category (){
    	return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category (){
    	return $this->belongsTo('\App\Models\ServiceCategory', 'service_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category_type (){
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->active()->withDefault(['title' => '']);
    }
}
