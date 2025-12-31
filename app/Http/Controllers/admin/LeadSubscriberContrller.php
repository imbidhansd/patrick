<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Models [start]
use App\Models\Lead;

class LeadSubscriberContrller extends Controller {

    public function __construct() {
        $this->modelObj = new Lead;

        //Post Types
        $this->post_type = 'leads';

        // View
        $this->view_base = 'admin.leads.subscribers.';
    }

    public function index() {
        $data = [
            'admin_page_title' => 'Manage Subscribers',
            'module_plural_name' => 'Manage Subscribers',
            'rows' => $this->modelObj->with(['state', 'lead_generate_for_company', 'lead_follow_up_emails'])->order()->paginate(env('APP_RECORDS_PER_PAGE')),
        ];
        
        return view($this->view_base . 'index', $data);
    }

}
