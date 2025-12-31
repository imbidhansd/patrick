<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Custom;
use App\Models\Company;
use App\Models\Trade;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\ServiceCategory;

class CompanyPriorityController extends Controller {

    public function __construct() {
        $this->view_base = 'admin.company_priority.';
    }

    public function index() {
        $admin_page_title = 'Search Company Proiority';

        $data = [
            'admin_page_title' => $admin_page_title,
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'top_level_categories' => [],
            'main_categories' => [],
            'service_categories' => [],
        ];

        return view($this->view_base . 'index', $data);
    }

    public function search(Request $request) {
        $validator = Validator::make($request->all(), [
                    'trade_id' => 'required',
                    'top_level_category_id' => 'required',
                    'main_category_id' => 'required',
                    'zipcode' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            $selected = [
                'trade_id' => $requestArr['trade_id'],
                'top_level_category_id' => $requestArr['top_level_category_id'],
                'main_category_id' => $requestArr['main_category_id'],
                'service_category_id' => null,
                'zipcode' => $requestArr['zipcode'],
                'mile_range' => null
            ];

            $top_level_categories = TopLevelCategory::active()
                    ->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', 'top_level_categories.id')
                    ->where('top_level_category_trades.trade_id', $requestArr['trade_id'])
                    ->orderBy('top_level_categories.title', 'ASC')
                    ->pluck('top_level_categories.title', 'top_level_categories.id');

            $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->where('main_category_top_level_categories.top_level_category_id', $requestArr['top_level_category_id'])
                    ->pluck('main_categories.title', 'main_categories.id');

            $service_categories = $service_category_list = $zipcodesArr = [];
            if (!isset($requestArr['service_category_id']) || $requestArr['service_category_id'] == '') {
                $service_categories = ServiceCategory::where([
                            ['main_category_id', $requestArr['main_category_id']],
                            ['top_level_category_id', $requestArr['top_level_category_id']]
                        ])
                        ->active()
                        ->order()
                        ->pluck('title', 'id');

                /* get service category list */
                $service_category_list = ServiceCategory::where([
                            ['top_level_category_id', $requestArr['top_level_category_id']],
                            ['main_category_id', $requestArr['main_category_id']]
                        ])
                        ->active()
                        ->order()
                        ->pluck('id')
                        ->toArray();
            } else {
                $service_category_list[] = $requestArr['service_category_id'];

                $service_categories = ServiceCategory::find($requestArr['service_category_id'])
                        ->active()
                        ->pluck('title', 'id');

                $selected['service_category_id'] = $requestArr['service_category_id'];
            }


            if (isset($requestArr['mile_range']) && $requestArr['mile_range'] != '') {

                $selected['mile_range'] = $requestArr['mile_range'];
                try {
                    $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);
                    if (count($zipCodes) > 0) {
                        $zipcodesArr = array_column($zipCodes, 'zip_code');
                    }
                } catch (Exception $ex) {
                    $zipcodesArr[] = $requestArr['zipcode'];
                }
            } else {
                $zipcodesArr[] = $requestArr['zipcode'];
            }


            //DB::enableQueryLog();

            $company_list = Company::select('companies.company_name', 'csc.service_category_id', 'csc.main_category_id', 'csc.service_category_type_id', 'cz.zip_code')
                    ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
                    ->leftJoin('company_service_categories AS csc', 'companies.id', 'csc.company_id')
                    ->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                    ->where([
                        ['ml.paid_members', 'yes'],
                        ['ml.lead_access', 'yes'],
                        ['ml.slug', '!=', 'accredited-member'],
                        ['ml.status', 'active'],
                        ['cz.status', 'active'],
                        ['csc.status', 'active'],
                    ])
                    ->whereIn('cz.zip_code', $zipcodesArr)
                    ->whereIn('csc.service_category_id', $service_category_list)
                    ->active()
                    ->orderBy('companies.approval_date', 'ASC')
                    ->orderBy('csc.service_category_type_id', 'ASC')
                    ->orderBy('csc.main_category_id', 'ASC')
                    ->get();

            //dd(DB::getQueryLog());
            //dd($company_list);

            $searchListArr = [];
            if (count($company_list) > 0) {
                foreach ($company_list AS $company_item) {
                    $service_category_type_id = $company_item->service_category_type_id;
                    $main_category_id = $company_item->main_category_id;
                    $service_category_id = $company_item->service_category_id;
                    $zipcode = $company_item->zip_code;


                    $searchListArr[$service_category_type_id][$main_category_id][$service_category_id][$zipcode][] = $company_item->company_name;
                }
            }

            $data = [
                'admin_page_title' => 'Search Company Proiority',
                'trades' => Trade::active()->order()->pluck('title', 'id'),
                'top_level_categories' => $top_level_categories,
                'main_categories' => $main_categories,
                'service_categories' => $service_categories,
                'selected' => $selected,
                'company_list' => $searchListArr
            ];

            return view($this->view_base . 'index', $data);
        }
    }

}
