<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategoryMainCategory extends Model {

    //
    protected $fillable = [
        'service_category_id', 'main_category_id',
    ];
    protected $table = 'service_category_main_categories';

    public function main_category() {
        return $this->belongsTo('App\Models\MainCategory')->withDefault(['title' => '']);
    }

    public function service_category() {
        return $this->belongsTo('App\Models\ServiceCategory')->withDefault(['title' => '']);
    }

}
