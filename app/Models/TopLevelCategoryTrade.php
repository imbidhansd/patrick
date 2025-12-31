<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopLevelCategoryTrade extends Model{
    //
    protected $fillable = [
        'top_level_category_id', 'trade_id', 'sort_order'
    ];
    protected $table = 'top_level_category_trades';

    public function trade(){
        return $this->belongsTo('App\Models\Trade')->withDefault(['title' => '']);
    }
    
    public function top_level_category(){
        return $this->belongsTo('App\Models\TopLevelCategory')->withDefault(['title' => '']);
    }
}
