<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategoryTopLevelCategory extends Model{
    //
    protected $fillable = [
        'top_level_category_id', 'main_category_id', 'sort_order'
    ];
    
    protected $table = 'main_category_top_level_categories';

    public function top_level_category(){
        return $this->belongsTo('App\Models\TopLevelCategory')->active()->withDefault(['title' => '']);
    }

    public function main_category(){
        return $this->belongsTo('App\Models\MainCategory')->active()->withDefault(['title' => '']);
    }
}
