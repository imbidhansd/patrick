<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use DB;
use App\Models\Company;
use App\Models\Lead;

class DashboardController extends Controller {

    public function __construct() {
        View::share('key', 'value');
    }

    public function index(Request $request) {
        $last_seven_days = \Carbon\Carbon::today()->subDays(7);
        $last_thirty_days = \Carbon\Carbon::today()->subDays(30);
        $last_six_month = \Carbon\Carbon::today()->subDays(180);


        $top_service_category = Lead::select(DB::raw('COUNT(id) AS total_leads'), 'service_category_id', 'main_category_id')
                ->with(['service_category', 'main_category'])
                ->groupBy('service_category_id')
                ->orderBy(DB::raw('COUNT(id)'), 'DESC')
                ->limit(5);

        /* last 30 days top service category */
        $top_thirty_days_service_category = $top_service_category->where('created_at', '>=', $last_thirty_days->format(env('DB_DATE_FORMAT')))->get();

        /* last 6 month top service category */
        $top_six_month_service_category = $top_service_category->where('created_at', '>=', $last_six_month->format(env('DB_DATE_FORMAT')))->get();

        $data = [
            'admin_page_title' => 'Dashboard',
            'todays_leads' => Lead::where('created_at', now()->format(env('DB_DATE_FORMAT')))->count(),
            'seven_days_leads' => Lead::where('created_at', '>=', $last_seven_days->format(env('DB_DATE_FORMAT')))->count(),
            'thirty_days_leads' => Lead::where('created_at', '>=', $last_thirty_days->format(env('DB_DATE_FORMAT')))->count(),
            'total_leads' => Lead::count(),
            'todays_companies' => Company::where(DB::raw('DATE(created_at)'), now()->format(env('DB_DATE_FORMAT')))->count(),
            'seven_days_companies' => Company::where(DB::raw('DATE(created_at)'), '>=', $last_seven_days->format(env('DB_DATE_FORMAT')))->count(),
            'thirty_days_companies' => Company::where(DB::raw('DATE(created_at)'), '>=', $last_thirty_days->format(env('DB_DATE_FORMAT')))->count(),
            'total_companies' => Company::count(),
            'recent_companies' => Company::select('companies.*', 'membership_statuses.color')->leftJoin('membership_statuses', 'companies.status', 'membership_statuses.title')->with(['membership_level', 'state', 'company_logo'])->orderBy('created_at', 'DESC')->limit('5')->get(),
            'recent_leads' => Lead::with(['service_category', 'main_category', 'state'])->orderBy('lead_active_date', 'DESC')->limit(5)->get(),
            'top_thirty_days_service_category' => $top_thirty_days_service_category,
            'top_six_month_service_category' => $top_six_month_service_category
        ];
        return view('admin.dashboard.index', $data);
    }

}
