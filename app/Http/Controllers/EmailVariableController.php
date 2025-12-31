<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class EmailVariableController extends Controller {

    public function email_variables() {
        $data['demo_custom_emails'] = DB::table('demo_custom_emails')->where('email_type', 'CUSTOM')->get();

        return view('demo_custom_emails', $data);
    }

    public function followup_variables() {
        $data['followup_variables'] = DB::table('demo_followup_templates')->get();

        return view('followup_variables', $data);
    }

}
