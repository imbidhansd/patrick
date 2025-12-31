<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateMainCategory extends Model
{
    protected $fillable = [
        'affiliate_id', 
        'main_category_id', 
        'aweber_member_listname', 
        'aweber_non_member_listname',
        'service_category_type_id',
        'aweber_member_listid',
        'aweber_non_member_listid'
    ];
    protected $table = 'affiliate_main_categories';

    // foreign references
    public function affiliate() {
        return $this->belongsTo('\App\Models\Affiliate', 'affiliate_id', 'id');
    }

    public function main_category() {
        return $this->belongsTo('\App\Models\MainCategory', 'main_category_id', 'id')->active();
    }

    public function service_category_type() {
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->withDefault(['title' => '']);
    }

}
