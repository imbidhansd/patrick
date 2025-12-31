<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartServiceCategory extends Model{
    //
    protected $fillable = [
        'company_id',
        'top_level_category_id', 'main_category_id', 'service_category_id', 'service_category_type_id',
        'fee',
        'status', 'category_type',
        'service_category_status', 'main_category_status'
    ];

    protected $table = 'shopping_cart_service_categories';

    // Foreign References
    public function top_level_category(){
        return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function main_category(){
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category(){
        return $this->belongsTo('\App\Models\ServiceCategory', 'service_category_id', 'id')->active()->withDefault(['title' => '']);
    }

    public function service_category_type (){
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->active()->withDefault(['title' => '']);
    }

    // Scope
    public function scopeActive($query){
        return $query->where($this->table . '.status', 'active');
    }

    public function scopeInactive($query){
        return $query->where($this->table . '.status', 'inactive');
    }
}
