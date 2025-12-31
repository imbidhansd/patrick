<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonMemberTopLevelCategory extends Model {

    protected $fillable = [
        'non_member_id', 'top_level_category_id'
    ];
    protected $table = 'non_member_top_level_categories';

    // foreign references
    public function non_member() {
        return $this->belongsTo('\App\Models\NonMember', 'non_member_id', 'id');
    }

    public function top_level_category() {
        return $this->belongsTo('\App\Models\TopLevelCategory', 'top_level_category_id', 'id')->active();
    }

}
