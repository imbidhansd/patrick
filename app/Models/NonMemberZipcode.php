<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonMemberZipcode extends Model {

    protected $fillable = [
        'non_member_id', 'zipcode', 'distance', 'city', 'state', 'state_id'
    ];
    protected $table = 'non_member_zipcodes';

    // Foreign References
    public function company() {
        $this->belongsTo('\App\Models\NonMember', 'non_member_id', 'id');
    }

    public function state() {
        $this->belongsTo('\App\Models\State', 'state_id', 'id')->withDefault(['name' => '']);
    }

}
