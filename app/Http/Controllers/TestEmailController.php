<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class TestEmailController extends Controller {

    public function company_who_didnt_get_lead() {
        $companyObj = Company::select('companies.id', 'companies.company_name', 'companies.leads_status', 'companies.status AS membership_status', 'membership_levels.title AS membership_level_title', 'companies.temporary_budget', 'membership_levels.charge_type', 'company_service_categories.fee')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                ->where([
                    ['membership_levels.paid_members', 'yes'],
                    ['membership_levels.lead_access', 'yes'],
                    ['membership_levels.slug', '!=', 'accredited-member'],
                    ['membership_levels.status', 'active'],
                    ['company_zipcodes.zip_code', '82081'],
                    ['company_zipcodes.status', 'active'],
                    ['company_service_categories.service_category_id', '3'],
                    ['company_service_categories.status', 'active'],
                ])
                ->orderBy('companies.created_at', 'ASC')
                ->get();

        $data = [
            'company_list' => $companyObj
        ];
        return view('mails.admin._company_list_who_didnt_get_lead', $data);
        dd($companyObj);
    }

}
