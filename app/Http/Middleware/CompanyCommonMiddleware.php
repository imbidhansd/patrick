<?php

namespace App\Http\Middleware;

use DB;
use Auth;
use App\Models\AffiliateMainCategory;
use App\Models\Lead;
use App\Models\Company;
use App\Models\CompanyLead;
use App\Models\CompanyMessage;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyProfileView;
use App\Models\MembershipLevelStatus;
use App\Models\Custom;
use Illuminate\Support\Collection;
use Closure;

class CompanyCommonMiddleware {

    public function handle($request, Closure $next) {
        // to share data in all views
        $company_id = Auth::guard('company_user')->user()->company_id;
        $company_item = Company::find($company_id);

        $paid_pending_cls = "";
        if ($company_item->status == 'Paid Pending') {
            $paid_pending_cls = "paid_pending";
        }

        if (!is_null($company_item->approval_date)) {
            $company_item->approval_date = Custom::date_formats($company_item->approval_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT'));
        }

        if (!is_null($company_item->renewal_date)) {
            $company_item->renewal_date = Custom::date_formats($company_item->renewal_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT'));
        }


        $membership_video = [];
        if (!is_null($company_item->status) && !is_null($company_item->membership_level_id)) {
            $membership_video = MembershipLevelStatus::select('membership_level_statuses.*')->leftJoin('membership_statuses', 'membership_level_statuses.membership_status_id', 'membership_statuses.id')
                    ->where([
                        ['membership_level_id', $company_item->membership_level_id],
                        ['membership_statuses.title', $company_item->status]
                    ])
                    ->first();
        }


        /* New leads this month */
        $new_this_month_leads = CompanyLead::where([
                    ['company_id', $company_item->id],
                    ['is_checked', 'no']
                ])
                ->where(DB::raw('MONTH(created_at)'), now()->format('m'))
                ->order()
                ->count();

        /* Total leads */
        $total_leads = CompanyLead::where([
                    ['company_id', $company_item->id],
                ])
                ->order()
                ->count();

        /* Find a pro leads */
        $company_page_lead_ids = Lead::where('lead_generate_for', $company_item->id)
                ->order()
                ->pluck('id')
                ->toArray();
        
        $find_a_pro_leads = CompanyLead::where('company_id', $company_item->id);
        if (count($company_page_lead_ids) > 0) {
            $find_a_pro_leads->whereNotIn('lead_id', $company_page_lead_ids);
        }
        $find_a_pro_leads = $find_a_pro_leads->order()->count();


        /* Company Page Leads */
        $company_page_leads = CompanyLead::leftJoin('leads', 'company_leads.lead_id', 'leads.id')
                ->where([
                    ['company_leads.company_id', $company_item->id],
                    ['leads.lead_generate_for', $company_item->id]
                ])
                ->order()
                ->count();


        // Page Views [Start]


        $all_profile_views = CompanyProfileView::where('company_id', $company_item->id)->count();
        $cur_month_profile_views = CompanyProfileView::where('company_id', $company_item->id)->whereMonth('created_at', now()->format('m'))->count();
        $last_month_profile_views = CompanyProfileView::where('company_id', $company_item->id)->whereMonth('created_at', now()->subMonth()->format('m'))->count();
        
        //dd($all_profile_views);


        // Page Views [End]
        $show_aad_banner = false;
        $company_service_categories = $company_item->service_category;
        $aad_categories = AffiliateMainCategory::where('affiliate_id', 1)->get();

        foreach ($company_service_categories as $company_service_category) {
            $main_category_id = $company_service_category['main_category_id'];
            $service_category_type_id = $company_service_category['service_category_type_id'];
            
            foreach ($aad_categories as $aad_category) {
                if ($aad_category['main_category_id'] == $main_category_id && $aad_category['service_category_type_id'] == $service_category_type_id) {
                    $show_aad_banner = true;
                    break;
                }
            }
        }        

        \View::share([
            'company_messages_notification' => CompanyMessage::where('company_id', $company_id)->notChecked()->active()->order()->limit(5)->get(),
            'company_item' => $company_item,
            'membership_video' => $membership_video,
            'company_approval_status' => CompanyApprovalStatus::where('company_id', $company_id)->first(),
            'user_item' => Auth::guard('company_user')->user(),
            'new_this_month_leads' => $new_this_month_leads,
            'total_leads' => $total_leads,
            'company_page_leads' => $company_page_leads,
            'find_a_pro_leads' => $find_a_pro_leads,
            'paid_pending_cls' => $paid_pending_cls,
            'global_settings' => Custom::getSettings(),
            //
            'all_profile_views' => $all_profile_views,
            'cur_month_profile_views' => $cur_month_profile_views,
            'last_month_profile_views' => $last_month_profile_views,
            'show_aad_banner' => $show_aad_banner
        ]);

        return $next($request);
    }

}
