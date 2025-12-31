<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use App\Models\CompanyLead;
use App\Models\CompanyInformation;
use App\Models\CompanyMessage;
use App\Models\News;
use App\Models\HideCompanyVideo;
use App\Models\MembershipStatus;

class DashboardController extends Controller {

    public function __construct() {
        $this->view_base = 'company.';
    }

    public function index(Request $request) {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $company_item = Auth::guard('company_user')->user()->company;

        /* leads count for chart */
        $chart_leads = CompanyLead::select('main_categories.title', DB::raw('COUNT(company_leads.id) AS total_leads'))
                ->leftJoin('leads', 'company_leads.lead_id', 'leads.id')
                ->leftJoin('main_categories', 'leads.main_category_id', 'main_categories.id')
                ->where('company_leads.company_id', $company_id)
                ->groupBy('leads.main_category_id')
                ->get();

        $leads_pie_chart = "";
        if (count($chart_leads) > 0) {
            foreach ($chart_leads AS $chart_lead_item) {
                $leads_pie_chart .= "['" . $chart_lead_item->title . "', " . $chart_lead_item->total_leads . "],";
            }
        }

        $membership_status = MembershipStatus::where('title', $company_item->status)->first();
        $hide_company_video = HideCompanyVideo::where([
                    ['company_id', $company_item->id],
                    ['membership_level_id', $company_item->membership_level_id],
                    ['membership_status_id', $membership_status->id]
                ])->latest()->first();
        $show_profile_update_screen = true;//TODO: this should be based on membership status

        $data = [
            'news' => News::active()->latest('date')->limit(1)->get(),
            'company_messages_notification' => CompanyMessage::where('company_id', $company_id)->notChecked()->active()->order()->limit(5)->get(),
            'company_information' => CompanyInformation::where('company_id', $company_id)->first(),
            'leads_pie_chart' => $leads_pie_chart,
            'hide_company_video' => $hide_company_video,
            'show_profile_update_screen' => $show_profile_update_screen
        ];

        return view($this->view_base . 'profile.dashboard', $data);
    }

    public function dismiss_dashboard_video(Request $request) {
        if (Session::get('video_dismissed') != '') {
            Session::forget('video_dismissed');
        } else {
            $companyObj = Auth::guard('company_user')->user()->company;
            $membership_status = MembershipStatus::where('title', $companyObj->status)->first();

            $item = HideCompanyVideo::create([
                        'company_id' => $companyObj->id,
                        'membership_level_id' => $companyObj->membership_level_id,
                        'membership_status_id' => $membership_status->id,
            ]);

            Session::put('video_dismissed', 'yes');
        }
    }

}
