<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use Illuminate\Support\Facades\Mail;
use DB;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\PackageProduct;
use App\Models\PackageServiceCategory;
use App\Models\Trade;
use App\Models\ServiceCategory;
use App\Models\MembershipLevel;
use App\Models\PreScreenSetting;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\Product;

class PackageController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $owner_qty = [
            '1' => '(1) One Owner',
            '2' => '(2) Two Owner',
            '3' => '(3) Three Owner',
            '4' => '(4) Four Owner'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'companies' => Company::order()->pluck('company_name', 'id'),
            'membership_levels' => MembershipLevel::paidMember()->active()->order()->pluck('title', 'id'),
            'owner_qty' => $owner_qty
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);

        $admin_page_title = 'Manage ' . $this->module_plural_name;

        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'packages.company_id' => [
                    'title' => 'Company',
                    'options' => $this->common_data['companies'],
                    'id' => 'company_id',
                    'class' => 'select2'
                ],
                'packages.membership_level_id' => [
                    'title' => 'Membership Level',
                    'options' => $this->common_data['membership_levels'],
                    'id' => 'membership_level_id'
                ],
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $package_companies = $this->modelObj->whereNotNull('company_id')
                ->active()
                ->order()
                ->pluck('company_id')
                ->toArray();

        if (count($package_companies) > 0) {
            $company_list = Company::whereNotIn('id', $package_companies)
                    ->order()
                    ->pluck('company_name', 'id');
        } else {
            $company_list = Company::whereNotIn('id', $package_companies)
                    ->order()
                    ->pluck('company_name', 'id');
        }

        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name,
            'company_list' => $company_list,
            'products' => Product::active()->order()->get(),
            'setup_fee' => PreScreenSetting::active()->find('2'),
            'membership_fee' => PreScreenSetting::active()->find('3'),
            'selected_products' => []
        ];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required|unique:packages,company_id',
                    'company_email' => 'required',
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['membership_fee'] = floatval($requestArr['membership_fee']);
            $requestArr['membership_total_fee'] = floatval($requestArr['membership_total_fee']);
            //dd($requestArr);
            $itemObj = $this->modelObj->create($requestArr);

            if (isset($requestArr['products']) && count($requestArr['products']) > 0) {
                PackageProduct::where('package_id', $itemObj->id)->delete();

                $product_total_fee = 0;
                foreach ($requestArr['products'] as $product_item) {
                    $product_price = $requestArr['product_price'][$product_item];

                    if (isset($product_price) && $product_price != '') {
                        $product_total_fee += $product_price;
                    }

                    $insertArr = [
                        'package_id' => $itemObj->id,
                        'product_id' => $product_item,
                        'product_price' => ((isset($product_price) && $product_price != '') ? $product_price : null)
                    ];

                    PackageProduct::create($insertArr);
                }

                $itemObj->suggested_product_total_fee = $product_total_fee;
                $itemObj->save();
            }

            flash($this->module_messages['add'])->success();

            //return redirect($this->urls['list']);
            return redirect('/admin/packages/service-categories/' . $itemObj->slug);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->with('package_products')->findOrFail($id);
        $package_companies = $this->modelObj->where('id', '!=', $formObj->id)
                        ->whereNotNull('company_id')
                        ->active()
                        ->order()
                        ->pluck('company_id')->toArray();

        if (count($package_companies) > 0) {
            $company_list = Company::whereNotIn('id', $package_companies)
                    ->order()
                    ->pluck('company_name', 'id');
        } else {
            $company_list = Company::whereNotIn('id', $package_companies)
                    ->order()
                    ->pluck('company_name', 'id');
        }

        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
            'company_list' => $company_list,
            'products' => Product::active()->order()->get(),
            'setup_fee' => PreScreenSetting::active()->find('2'),
            'membership_fee' => PreScreenSetting::active()->find('3'),
            'selected_products' => PackageProduct::where('package_id', $formObj->id)->pluck('product_id')->toArray()
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'company_id' => 'required|unique:packages,company_id,' . $id . ',id',
                    'company_email' => 'required',
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $before_membership_level = $itemObj->membership_level_id;
            $after_membership_level = $requestArr['membership_level_id'];

            $requestArr['membership_fee'] = floatval($requestArr['membership_fee']);
            $requestArr['membership_total_fee'] = floatval($requestArr['membership_total_fee']);
            $itemObj->update($requestArr);
            $final_total_fee = $leads_total_fee = 0;

            if (isset($requestArr['products']) && count($requestArr['products']) > 0) {
                PackageProduct::where('package_id', $itemObj->id)->delete();

                $product_total_fee = 0;
                foreach ($requestArr['products'] as $product_item) {
                    $product_price = $requestArr['product_price'][$product_item];

                    if (isset($product_price) && $product_price != '') {
                        $product_total_fee += $product_price;
                    }

                    $insertArr = [
                        'package_id' => $itemObj->id,
                        'product_id' => $product_item,
                        'product_price' => ((isset($product_price) && $product_price != '') ? $product_price : null)
                    ];

                    PackageProduct::create($insertArr);
                }

                $itemObj->suggested_product_total_fee = $product_total_fee;
                $itemObj->save();
            }


            $package_service_category = PackageServiceCategory::where('package_id', $itemObj->id)->get();
            if (count($package_service_category) > 0) {
                $membership_levels = ['4', '5'];

                foreach ($package_service_category as $package_service_category_item) {
                    $fee = $package_service_category_item->fee;

                    if ($requestArr['membership_level_id'] == '4') {
                        if ($before_membership_level != $after_membership_level) {
                            $fee = $package_service_category_item->main_category->annual_price;
                        }
                    } else if ($requestArr['membership_level_id'] == '5') {
                        if ($before_membership_level != $after_membership_level) {
                            $fee = $package_service_category_item->main_category->monthly_price;
                        }
                    } else if ($requestArr['membership_level_id'] == '6') {
                        if ($before_membership_level != $after_membership_level) {
                            if (is_null($package_service_category_item->service_category->ppl_price)) {
                                $fee = $package_service_category_item->main_category->ppl_price;
                            } else {
                                $fee = $package_service_category_item->service_category->ppl_price;
                            }

                            if (in_array($itemObj->membership_level_id, $membership_levels)) {
                                $leads_total_fee += $fee;
                            }
                        }
                    }

                    $package_service_category_item->fee = $fee;
                    $package_service_category_item->save();
                }

                if (in_array($itemObj->membership_level_id, $membership_levels)) {
                    $package_service = PackageServiceCategory::select(DB::raw('DISTINCT main_category_id, service_category_type_id, fee'))->where('package_id', $itemObj->id)->get();

                    foreach ($package_service as $service_item) {
                        $leads_total_fee += $service_item->fee;
                    }
                }
            }

            $final_total_fee = str_replace(",", "", $itemObj->membership_total_fee) + str_replace(",", "", $itemObj->suggested_product_total_fee) + $leads_total_fee;

            $itemObj->final_total_fee = $final_total_fee;
            $itemObj->leads_total_fee = $leads_total_fee;
            $itemObj->save();

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;
        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->warning();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function package_service_categories($slug, Request $request) {
        $package = $this->modelObj::with('package_products')->whereSlug($slug)->first();

        if (is_null($package)) {
            flash('Package not found.')->error();
            return back();
        }

        $data['admin_page_title'] = $this->singular_display_name . ' service categories';
        $data['package'] = $package;
        $data['trades'] = Trade::active()->order()->pluck('title', 'id');
        $data['package_service_category_list'] = PackageServiceCategory::select(['main_category_id', 'service_category_id', 'service_category_type_id', 'fee'])->where('package_id', $package->id)->get()->toArray();

        return view($this->view_base . '.package_service_categories', $data);
    }

    public function postServiceCategories(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'trade_id' => 'required',
                    'main_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $final_total_fee = $leads_total_fee = 0;

            $package = $this->modelObj::active()->find($requestArr['package_id']);
            $package->trade_id = $requestArr['trade_id'];

            $companyObj = Company::find($package->company_id);

            if (isset($requestArr['main_category_id'])) {
                $package->main_category_id = $requestArr['main_category_id'];
            }

            if (isset($requestArr['secondary_main_category_id'])) {
                $package->secondary_main_category_id = $requestArr['secondary_main_category_id'];
            }

            if (isset($requestArr['top_level_category_ids'])) {
                $package->top_level_categories = json_encode($requestArr['top_level_category_ids']);
            }

            if (isset($requestArr['service_category_ids'])) {
                $package->service_categories = json_encode($requestArr['service_category_ids']);
            }

            if (isset($requestArr['include_rest_categories'])) {
                $package->include_rest_categories = 'no';
            } else {
                $package->include_rest_categories = 'yes';
            }

            if (isset($requestArr['service_category_ids']) && count($requestArr['service_category_ids']) > 0) {
                PackageServiceCategory::where('package_id', $package->id)->delete();

                $service_category = ServiceCategory::select(['id', 'top_level_category_id', 'main_category_id', 'service_category_type_id'])->active()->whereIn('id', $requestArr['service_category_ids'])->get();
                //$temp_main_category_id = $temp_service_category_type_id = "";
                foreach ($service_category as $service_category_item) {

                    $insertArr = [
                        'package_id' => $requestArr['package_id'],
                        'top_level_category_id' => $service_category_item->top_level_category_id,
                        'main_category_id' => $service_category_item->main_category_id,
                        'service_category_id' => $service_category_item->id,
                        'service_category_type_id' => $service_category_item->service_category_type_id
                    ];


                    if (($package->membership_level_id == '4' || $package->membership_level_id == '5') && isset($requestArr['main_service_category_fee'][$service_category_item->service_category_type_id][$service_category_item->main_category_id])) {

                        $insertArr['fee'] = $requestArr['main_service_category_fee'][$service_category_item->service_category_type_id][$service_category_item->main_category_id];

                        PackageServiceCategory::create($insertArr);
                    } else if ($package->membership_level_id == '6' && isset($requestArr['service_category_fee'][$service_category_item->service_category_type_id][$service_category_item->id])) {

                        $insertArr['fee'] = $requestArr['service_category_fee'][$service_category_item->service_category_type_id][$service_category_item->id];

                        PackageServiceCategory::create($insertArr);

                        $leads_total_fee += $insertArr['fee'];
                    }
                }


                if ($package->membership_level_id == '4' || $package->membership_level_id == '5') {
                    $package_service = PackageServiceCategory::select(DB::raw('DISTINCT main_category_id, service_category_type_id, fee'))->where('package_id', $package->id)->get();

                    foreach ($package_service as $service_item) {
                        $leads_total_fee += $service_item->fee;
                    }
                } else {
                    $leads_total_fee = 0; // 0 for PPL and Accridiation
                }
            }


            if (is_null($package->package_code)) {
                $package->package_code = Custom::getRandomString(8);
            }
            //$requestArr['package_code'] = Custom::getRandomString(5);

            $package->leads_total_fee = $leads_total_fee;

            //$final_total_fee = $package->todays_total_fee + $package->membership_total_fee + $package->leads_total_fee + $package->suggested_product_total_fee;
            $final_total_fee = str_replace(",", "", $package->membership_total_fee) + str_replace(",", "", $package->suggested_product_total_fee) + $leads_total_fee;
            $package->final_total_fee = $final_total_fee;
            $package->save();

            flash("Service categories added successfully.")->success();
            return redirect($this->urls['list']);
        }
    }

    /* Ajax call functions */

    public function get_fees(Request $request) {
        $requestArr = $request->all();

        if (isset($requestArr['type'])) {
            $fees = 0;

            if ($requestArr['type'] == 'membership' && isset($requestArr['level_id'])) {
                $setting_id = "";
                if ($requestArr['level_id'] == '4')
                    $setting_id = "3";
                else if ($requestArr['level_id'] == '5')
                    $setting_id = "4";
                else if ($requestArr['level_id'] == '6')
                    $setting_id = "6";

                $membership_fee = CompanyChargeSetting::active()->find($setting_id);

                if (!is_null($membership_fee)) {
                    $fees = $membership_fee->amount;
                }
            } else if ($requestArr['type'] == 'owner_selection' && isset($requestArr['owners_qty'])) {
                $setting_id = [];

                if ($requestArr['owners_qty'] == 1)
                    array_push($setting_id, '1');
                else
                    array_push($setting_id, '1', '4');

                $owners_fee = PreScreenSetting::active()->whereIn('id', $setting_id)->get(['id', 'price']);

                if (!is_null($owners_fee)) {
                    $owners_charge = $first_owner_fee = $other_owner_free = 0;

                    foreach ($owners_fee as $charge_item) {
                        if ($requestArr['owners_qty'] != 1 && $charge_item->id == 4) {
                            $other_owner_free = $charge_item->price * ($requestArr['owners_qty'] - 1);
                            //$owners_charge += $charge_item->price * ($requestArr['owners_qty'] - 1);
                        } else {
                            $first_owner_fee = $charge_item->price;
                            //$owners_charge += $charge_item->price;
                        }
                    }

                    //$fees = $owners_charge;

                    return [
                        'success' => 1,
                        'first_owner_fee' => number_format($first_owner_fee, 2, '.', ''),
                        'other_owner_fee' => number_format($other_owner_free, 2, '.', ''),
                    ];
                }
            }


            if ($fees > 0) {
                return [
                    'success' => 1,
                    'fees' => number_format($fees, 2, '.', '')
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => ''
                ];
            }
        } else {
            return [
                'success' => 0,
                'message' => ''
            ];
        }
    }

    /* Ajax Call functions for get categories */

    public function getTopLevelCategoryList(Request $request) {
        $top_level_categories = null;
        // Find Top Level Categories from trade_id

        if ($request->has('trade_id') && $request->get('trade_id') > 0) {
            $top_level_categories = TopLevelCategory::active()
                    ->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', 'top_level_categories.id')
                    ->where('top_level_category_trades.trade_id', $request->get('trade_id'))
                    //->orderBy('top_level_category_trades.sort_order', 'ASC')
                    ->orderBy('top_level_categories.title', 'ASC')
                    ->select('top_level_categories.title', 'top_level_categories.id')
                    ->get();
        }

        $data = [
            'top_level_categories' => $top_level_categories,
            'show_back_btn' => $request->has('show_back_btn') ? true : false,
        ];
        return view($this->view_base . '._top_level_categories', $data);
    }

    public function getMainCategoryList(Request $request) {
        $main_categories = null;
        if ($request->has('top_level_category_ids') && count($request->get('top_level_category_ids')) > 0) {

            $query = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->whereIn('main_category_top_level_categories.top_level_category_id', $request->get('top_level_category_ids'))
                    ->active()
                    ->orderBy('main_categories.title', 'ASC')
                    ->select('main_categories.*');

            if ($request->has('main_category_id') && $request->get('main_category_id') > 0) {
                $data['show_none'] = true;
                $query->where('main_categories.id', '!=', $request->get('main_category_id'));
            }

            $main_categories = $query->get();

            if (is_null($main_categories) || count($main_categories) <= 0) {
                return 'false';
            }

            $data = ['main_categories' => $main_categories];

            return view($this->view_base . '._main_categories', $data);
        }
    }

    public function getServiceCategoryList(Request $request) {
        $service_categories = null;

        if ($request->has('main_category_id') && $request->get('main_category_id') != '') {

            $service_categories = ServiceCategory::where('main_category_id', $request->get('main_category_id'))
                    ->active()
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('sort_order', 'ASC')
                    ->get();

            $service_category_arr = [];

            if (!is_null($service_categories)) {
                foreach ($service_categories as $service_category_item) {
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_id'] = $service_category_item->service_category_type_id;

                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_title'] = $service_category_item->service_category_type->title;

                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_id'] = $service_category_item->main_category_id;

                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_title'] = $service_category_item->main_category->title;

                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['service_categories'][] = $service_category_item;
                }
            }

            $data = [
                'service_categories' => $service_categories,
                'service_category_arr' => $service_category_arr
            ];

            if ($request->has('price_display') && $request->get('price_display') != '') {
                $data['price_display'] = 'yes';
                $data['package_membership_type'] = $request->get('package_membership_type');
                $data['package_id'] = $request->get('package_id');
            }

            return view($this->view_base . '._service_categories', $data);
        }
    }

    public function getRestCategoryList(Request $request) {
        $service_categories = null;
        if ($request->has('main_category_id') && $request->get('main_category_id') != '') {
            $query = ServiceCategory::whereIn('top_level_category_id', $request->get('top_level_category_ids'))
                    ->active()
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('sort_order', 'ASC');

            if ($request->has('main_category_id') && $request->get('main_category_id') > 0) {
                $query->where('main_category_id', '!=', $request->get('main_category_id'));
            }
            if ($request->has('secondary_main_category_id') && $request->get('secondary_main_category_id') > 0) {
                $query->where('main_category_id', '!=', $request->get('secondary_main_category_id'));
            }

            $service_categories = $query->get();

            $service_category_arr = [];

            if (!is_null($service_categories)) {
                foreach ($service_categories as $service_category_item) {
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_id'] = $service_category_item->service_category_type_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_title'] = $service_category_item->service_category_type->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_id'] = $service_category_item->main_category_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_title'] = $service_category_item->main_category->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['service_categories'][] = $service_category_item;
                }
            }

            //dd($service_category_arr);
            $data = [
                'service_categories' => $service_categories,
                'service_category_arr' => $service_category_arr,
                'package_membership_type' => $request->get('package_membership_type'),
                'package_id' => $request->get('package_id'),
            ];

            return view($this->view_base . '._rest_service_categories', $data);
        }
    }

    /* get company first owner email */

    public function get_company_owner_email(Request $request) {
        $requestArr = $request->all();

        $company_user_obj = CompanyUser::where([
                    ['company_id', $requestArr['company_id']],
                    ['company_user_type', 'company_super_admin']
                ])
                ->select('email')
                ->first();

        if (!is_null($company_user_obj)) {
            return [
                'success' => 1,
                'message' => 'Company Email found.',
                'email' => $company_user_obj->email
            ];
        } else {
            return [
                'success' => 0,
                'message' => 'Company Email not found.'
            ];
        }
    }

    public function sendPackageEmail($id, Request $request) {
        $package = $this->modelObj::active()->findOrFail($id);

        if ($package->package_code != '') {
            $web_settings = Custom::getSettings();
            $companyUserObj = CompanyUser::where([
                        ['company_id', $package->company_id],
                        ['company_user_type', 'company_super_admin']
                    ])->first();
            $companyObj = Company::find($package->company_id);
            $company_mail_id = "79"; // Mail Title: Company Package Generated 
            $replaceWithArr = [
                'company_name' => $companyObj->company_name,
                'package_code' => $package->package_code,
                'upgrade_link' => url('dashboard'),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $companyUserObj->email,
                'date' => $package->created_at->format(env('DATE_FORMAT')),
                'url' => url('dashboard'),
                'email_footer' => $companyUserObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('dashboard')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceWithArr);
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($company_mail_id, $replaceWithArr));
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
                }
            }
            flash('Package code has been sent to company')->success();
            return back();
        } else {
            flash('Package code is not has been generated yet. Please select categories first')->error();
            return redirect(route('package-service-categories', ['slug' => $package->slug]));
        }
    }

}
